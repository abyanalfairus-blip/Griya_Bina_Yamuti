<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\Donation;
use App\Models\Gallery;
use App\Models\Message;
use App\Models\Report;
use App\Models\TeamMember;
use App\Support\ReportBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about', ['team' => TeamMember::orderBy('sort_order')->orderBy('id')->get()]);
    }

    public function programOverview()
    {
        return view('pages.program-overview');
    }

    public function zakat()
    {
        return view('pages.zakat');
    }

    public function reports(Request $request)
    {
        $tab = in_array($request->query('jenis'), ['bulanan', 'tahunan', 'keuangan', 'penyaluran'], true)
            ? $request->query('jenis') : 'bulanan';

        return view('pages.reports', [
            'tab' => $tab,
            'periods' => ReportBuilder::periods(),
            'reports' => Report::published()->orderByDesc('period_date')->get()->groupBy('type'),
            'distributions' => Distribution::with('program')->latest('distributed_at')->take(30)->get(),
            'totalIn' => (int) Donation::paid()->sum('amount'),
            'totalOut' => (int) Distribution::sum('amount'),
            'donorCount' => Donation::paid()->count(),
        ]);
    }

    public function reportMonth(int $year, int $month)
    {
        $from = Carbon::create($year, $month, 1)->startOfMonth();
        abort_if($from->gt(now()), 404);

        return view('pages.report-month', [
            'from' => $from,
            's' => ReportBuilder::summary($from, $from->copy()->endOfMonth()),
            'docs' => Report::published()->where('type', 'bulanan')
                ->whereYear('period_date', $year)->whereMonth('period_date', $month)->get(),
            'prev' => $from->copy()->subMonth(),
            'next' => $from->copy()->addMonth()->lte(now()) ? $from->copy()->addMonth() : null,
        ]);
    }

    public function reportYear(int $year)
    {
        $from = Carbon::create($year, 1, 1)->startOfYear();
        abort_if($from->gt(now()), 404);

        $flat = ReportBuilder::periods()['flat'];
        $lastMonth = $year === now()->year ? now()->month : 12;
        $rows = collect(range(1, $lastMonth))->map(function ($m) use ($year, $flat) {
            $k = sprintf('%04d-%02d', $year, $m);
            return [
                'month' => $m,
                'label' => Carbon::create($year, $m, 1)->translatedFormat('F'),
                'in' => $flat[$k]['in'] ?? 0,
                'out' => $flat[$k]['out'] ?? 0,
            ];
        });

        return view('pages.report-year', [
            'year' => $year,
            's' => ReportBuilder::summary($from, $from->copy()->endOfYear()),
            'rows' => $rows,
            'docs' => Report::published()->where('type', 'tahunan')->whereYear('period_date', $year)->get(),
        ]);
    }

    public function gallery()
    {
        return view('pages.gallery', ['galleries' => Gallery::latest()->paginate(12)]);
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:120',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:150',
            'body' => 'required|string|max:2000',
        ], ['name.required' => 'Nama wajib diisi.', 'body.required' => 'Pesan wajib diisi.']);

        Message::create($data);

        return back()->with('success', 'Pesan Anda sudah kami terima. Terima kasih telah menghubungi kami.');
    }
}
