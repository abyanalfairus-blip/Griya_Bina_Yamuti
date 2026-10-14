<?php

namespace App\Support;

use App\Models\Distribution;
use App\Models\Donation;
use Illuminate\Support\Carbon;

/** Menyusun rekap laporan otomatis dari donasi berstatus "paid" dan data penyaluran. */
class ReportBuilder
{
    public static function summary(Carbon $from, Carbon $to): array
    {
        $donations = Donation::paid()
            ->whereBetween('paid_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->with('program:id,title')
            ->get();

        $distributions = Distribution::with('program:id,title')
            ->whereBetween('distributed_at', [$from->toDateString(), $to->toDateString()])
            ->orderBy('distributed_at')
            ->get();

        $in = (int) $donations->sum('amount');
        $out = (int) $distributions->sum('amount');

        return [
            'from' => $from,
            'to' => $to,
            'in' => $in,
            'out' => $out,
            'balance' => $in - $out,
            'count' => $donations->count(),
            'byType' => $donations->groupBy('type')->map(fn ($g) => (int) $g->sum('amount'))->sortDesc(),
            'byProgram' => $donations
                ->groupBy(fn ($d) => $d->program?->title ?? 'Umum (tidak terikat program)')
                ->map(fn ($g) => (int) $g->sum('amount'))->sortDesc(),
            'distributions' => $distributions,
        ];
    }

    /**
     * Daftar periode yang memiliki data.
     * Hasil: ['months' => [tahun => ['2026-08' => ['in' => , 'out' => ]]], 'years' => [tahun => ['in' => , 'out' => ]]]
     */
    public static function periods(): array
    {
        $flat = [];

        foreach (Donation::paid()->whereNotNull('paid_at')->get(['amount', 'paid_at']) as $d) {
            $k = $d->paid_at->format('Y-m');
            $flat[$k] ??= ['in' => 0, 'out' => 0];
            $flat[$k]['in'] += $d->amount;
        }
        foreach (Distribution::get(['amount', 'distributed_at']) as $d) {
            $k = $d->distributed_at->format('Y-m');
            $flat[$k] ??= ['in' => 0, 'out' => 0];
            $flat[$k]['out'] += $d->amount;
        }

        krsort($flat);

        $months = [];
        $years = [];
        foreach ($flat as $ym => $v) {
            $y = substr($ym, 0, 4);
            $months[$y][$ym] = $v;
            $years[$y] ??= ['in' => 0, 'out' => 0];
            $years[$y]['in'] += $v['in'];
            $years[$y]['out'] += $v['out'];
        }

        return ['months' => $months, 'years' => $years, 'flat' => $flat];
    }
}
