<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $donations = Donation::with('program')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('account', [
            'donations' => $donations,
            'totalPaid' => $donations->where('status', 'paid')->sum('amount'),
        ]);
    }
}
