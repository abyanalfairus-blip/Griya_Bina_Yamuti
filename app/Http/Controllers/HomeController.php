<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\Donation;
use App\Models\Post;
use App\Models\Program;

class HomeController extends Controller
{
    /** Program unggulan di landing page (slug), sesuai urutan tampil. */
    public const FLAGSHIP = ['penyaluran-sepatu', 'rumah-belajar', 'santunan-anak-yatim'];

    public function index()
    {
        return view('home', [
            'flagship' => Program::active()->whereIn('slug', self::FLAGSHIP)->get()->keyBy('slug'),
            'others' => Program::active()->whereNotIn('slug', self::FLAGSHIP)->orderByDesc('is_featured')->latest()->take(3)->get(),
            'posts' => Post::published()->latest('published_at')->take(3)->get(),
            'latestDonations' => Donation::paid()->with('program')->latest('paid_at')->take(6)->get(),
            'stats' => [
                'donors' => Donation::paid()->count(),
                'collected' => (int) Donation::paid()->sum('amount'),
                'distributed' => (int) Distribution::sum('amount'),
                'programs' => Program::active()->count(),
            ],
        ]);
    }
}
