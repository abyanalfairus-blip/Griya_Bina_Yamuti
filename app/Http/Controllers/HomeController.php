<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\Donation;
use App\Models\Gallery;
use App\Models\PaymentMethod;
use App\Models\Post;
use App\Models\Program;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Program::active()->orderByDesc('is_featured')->latest()->first();
        $programs = Program::active()->orderByDesc('is_featured')->latest()->take(6)->get();
        $posts = Post::published()->latest('published_at')->take(3)->get();
        $latestDonations = Donation::paid()->with('program')->latest('paid_at')->take(8)->get();
        $galleries = Gallery::latest()->take(6)->get();

        $stats = [
            'donors' => Donation::paid()->count(),
            'collected' => (int) Donation::paid()->sum('amount'),
            'distributed' => (int) Distribution::sum('amount'),
            'programs' => Program::active()->count(),
        ];

        return view('home', compact('featured', 'programs', 'posts', 'latestDonations', 'galleries', 'stats') + [
            'methods' => PaymentMethod::active()->get(),
        ]);
    }
}
