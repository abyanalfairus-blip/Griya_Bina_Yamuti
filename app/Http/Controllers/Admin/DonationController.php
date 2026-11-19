<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\PaymentMethod;
use App\Models\Program;
use Illuminate\Support\Str;
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

    /** Formulir untuk mencatat donasi yang dilaporkan lewat WhatsApp, transfer langsung, atau tunai. */
    public function create()
    {
        return view('admin.donations.create', [
            'programs' => Program::orderBy('title')->get(['id', 'title']),
            'methods' => PaymentMethod::orderBy('sort_order')->orderBy('id')->get(['id', 'name', 'account_number', 'is_active']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'source' => 'required|in:whatsapp,transfer,tunai',
            'donor_name' => 'required|string|max:100',
            'donor_phone' => 'nullable|string|max:20',
            'is_anonymous' => 'nullable|boolean',
            'type' => 'required|in:' . implode(',', array_keys(Donation::TYPES)),
            'program_id' => 'nullable|exists:programs,id',
            'amount' => 'required|integer|min:1|max:1000000000',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'paid_on' => 'required|date|before_or_equal:today',
            'proof' => 'nullable|image|max:4096',
            'message' => 'nullable|string|max:500',
            'admin_note' => 'nullable|string|max:500',
            'confirmed' => 'nullable|boolean',
        ], [
            'donor_name.required' => 'Nama donatur wajib diisi (tulis "Hamba Allah" bila tidak disebutkan).',
            'amount.required' => 'Nominal wajib diisi sesuai dana yang masuk.',
            'paid_on.before_or_equal' => 'Tanggal diterima tidak boleh di masa depan.',
            'proof.image' => 'Bukti harus berupa gambar (JPG atau PNG).',
            'proof.max' => 'Ukuran gambar bukti maksimal 4 MB.',
        ]);

        $confirmed = $request->boolean('confirmed');
        $paidAt = \Illuminate\Support\Carbon::parse($data['paid_on'])->setTimeFrom(now());

        $donation = Donation::create([
            'invoice' => 'GBY' . $paidAt->format('ymd') . strtoupper(Str::random(5)),
            'source' => $data['source'],
            'donor_name' => $data['donor_name'],
            'donor_phone' => $data['donor_phone'] ?? null,
            'is_anonymous' => $request->boolean('is_anonymous'),
            'type' => $data['type'],
            'program_id' => $data['program_id'] ?? null,
            'amount' => $data['amount'],
            'unique_code' => 0,
            'payment_method_id' => $data['source'] === 'tunai' ? null : ($data['payment_method_id'] ?? null),
            'proof' => $request->hasFile('proof') ? $request->file('proof')->store('proofs', 'public') : null,
            'message' => $data['message'] ?? null,
            'admin_note' => $data['admin_note'] ?? null,
            'status' => $confirmed ? 'paid' : 'review',
            'paid_at' => $confirmed ? $paidAt : null,
            'created_at' => $paidAt,
        ]);

        $donation->program?->recalculate();

        return redirect()->route('admin.donations.show', $donation)->with('success', $confirmed
            ? 'Donasi tercatat dan langsung dihitung ke program. Jangan lupa kirim ucapan terima kasih ke donatur.'
            : 'Donasi tercatat sebagai "Menunggu verifikasi". Ubah status menjadi Diterima setelah dana dicek di rekening.');
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
