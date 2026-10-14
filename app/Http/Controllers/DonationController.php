<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\PaymentMethod;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    public function create(Request $request)
    {
        return view('donation.create', [
            'programs' => Program::active()->orderBy('title')->get(),
            'methods' => PaymentMethod::active()->get(),
            'selectedProgram' => $request->integer('program') ?: null,
            'type' => in_array($request->type, array_keys(Donation::TYPES)) ? $request->type : 'donasi',
            'amount' => max(0, $request->integer('amount')),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'program_id' => 'nullable|exists:programs,id',
            'type' => 'required|in:' . implode(',', array_keys(Donation::TYPES)),
            'amount' => 'required|integer|min:10000|max:1000000000',
            'donor_name' => 'required|string|max:100',
            'donor_phone' => 'nullable|string|max:20',
            'donor_email' => 'nullable|email|max:120',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'message' => 'nullable|string|max:500',
        ], [
            'amount.min' => 'Nominal donasi minimal Rp 10.000.',
            'donor_name.required' => 'Nama wajib diisi.',
            'payment_method_id.required' => 'Pilih metode pembayaran.',
        ]);

        $donation = Donation::create($data + [
            'user_id' => $request->user()?->id,
            'invoice' => 'GBY' . now()->format('ymd') . strtoupper(Str::random(5)),
            'unique_code' => random_int(11, 499),
            'is_anonymous' => $request->boolean('is_anonymous'),
            'status' => 'pending',
        ]);

        return redirect()->route('donation.show', $donation->invoice);
    }

    public function show(Donation $donation)
    {
        return view('donation.show', ['donation' => $donation->load(['program', 'paymentMethod'])]);
    }

    public function uploadProof(Request $request, Donation $donation)
    {
        abort_if($donation->status === 'paid', 403);

        $request->validate(['proof' => 'required|image|max:3072'], [
            'proof.required' => 'Pilih foto bukti transfer.',
            'proof.image' => 'Berkas harus berupa gambar (JPG atau PNG).',
            'proof.max' => 'Ukuran gambar maksimal 3 MB.',
        ]);

        if ($donation->proof) {
            Storage::disk('public')->delete($donation->proof);
        }

        $donation->update([
            'proof' => $request->file('proof')->store('proofs', 'public'),
            'status' => 'review',
        ]);

        return back()->with('success', 'Bukti transfer terkirim. Tim kami akan memverifikasinya secepat mungkin.');
    }
}
