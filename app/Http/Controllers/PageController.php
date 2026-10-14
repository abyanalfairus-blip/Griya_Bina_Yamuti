<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\Donation;
use App\Models\Gallery;
use App\Models\Message;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about', ['team' => TeamMember::orderBy('sort_order')->orderBy('id')->get()]);
    }

    public function zakat()
    {
        return view('pages.zakat');
    }

    public function reports()
    {
        return view('pages.reports', [
            'distributions' => Distribution::with('program')->latest('distributed_at')->paginate(10),
            'totalIn' => (int) Donation::paid()->sum('amount'),
            'totalOut' => (int) Distribution::sum('amount'),
            'donorCount' => Donation::paid()->count(),
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
