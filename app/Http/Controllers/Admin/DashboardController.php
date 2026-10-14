<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Message;
use App\Models\Post;
use App\Models\Program;

class DashboardController extends Controller
{
    public function index()
    {
        $monthly = Donation::paid()
            ->where('paid_at', '>=', now()->subMonths(5)->startOfMonth())
            ->get()
            ->groupBy(fn ($d) => $d->paid_at->format('Y-m'))
            ->map(fn ($g) => (int) $g->sum('amount'));

        $months = collect(range(5, 0))->map(function ($i) use ($monthly) {
            $date = now()->subMonths($i);
            return ['label' => $date->translatedFormat('M'), 'value' => $monthly[$date->format('Y-m')] ?? 0];
        });

        return view('admin.dashboard', [
            'stats' => [
                'collected' => (int) Donation::paid()->sum('amount'),
                'pending' => Donation::whereIn('status', ['pending', 'review'])->count(),
                'review' => Donation::where('status', 'review')->count(),
                'programs' => Program::active()->count(),
                'posts' => Post::count(),
                'messages' => Message::where('is_read', false)->count(),
            ],
            'months' => $months,
            'latest' => Donation::with('program')->latest()->take(8)->get(),
        ]);
    }
}
