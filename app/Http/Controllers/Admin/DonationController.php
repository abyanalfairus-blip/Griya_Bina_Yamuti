<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $donations = Donation::with(['program', 'paymentMethod'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($w) => $w
                ->where('donor_name', 'like', '%' . $request->q . '%')
                ->orWhere('invoice', 'like', '%' . $request->q . '%')))
            ->latest()->paginate(15)->withQueryString();

        return view('admin.donations.index', compact('donations'));
    }

    public function show(Donation $donation)
    {
        return view('admin.donations.show', ['donation' => $donation->load(['program', 'paymentMethod'])]);
    }

    public function update(Request $request, Donation $donation)
    {
        $data = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Donation::STATUSES)),
            'admin_note' => 'nullable|string|max:500',
        ]);

        $donation->update($data + [
            'paid_at' => $data['status'] === 'paid' ? ($donation->paid_at ?? now()) : null,
        ]);

        $donation->program?->recalculate();

        return redirect()->route('admin.donations.show', $donation)->with('success', 'Status donasi diperbarui.');
    }

    public function destroy(Donation $donation)
    {
        $program = $donation->program;
        if ($donation->proof) {
            Storage::disk('public')->delete($donation->proof);
        }
        $donation->delete();
        $program?->recalculate();

        return redirect()->route('admin.donations.index')->with('success', 'Donasi dihapus.');
    }
}
