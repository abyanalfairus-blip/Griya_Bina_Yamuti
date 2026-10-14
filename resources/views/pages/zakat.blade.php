@extends('layouts.app')
@section('title', 'Kalkulator zakat')
@section('description', 'Hitung zakat penghasilan, zakat maal, dan zakat fitrah dengan mudah.')
@section('content')
@include('partials.page-header', ['title' => 'Kalkulator zakat', 'subtitle' => 'Hitung kewajiban zakat Anda, lalu tunaikan langsung lewat yayasan.'])

<section class="mx-auto max-w-3xl px-4 pt-10"
    x-data="{
        tab: 'penghasilan',
        gold: {{ (int) setting('gold_price') }},
        fitrahRate: {{ (int) setting('fitrah_rate') }},
        income: 0, other: 0,
        savings: 0, goldAssets: 0, assets: 0, debt: 0,
        people: 1,
        get nisab() { return this.gold * 85 },
        get incomeTotal() { return (+this.income || 0) + (+this.other || 0) },
        get penghasilan() { return this.incomeTotal >= this.nisab / 12 ? Math.round(this.incomeTotal * 0.025) : 0 },
        get wealth() { return (+this.savings||0) + (+this.goldAssets||0) + (+this.assets||0) - (+this.debt||0) },
        get maal() { return this.wealth >= this.nisab ? Math.round(this.wealth * 0.025) : 0 },
        get fitrah() { return (+this.people||0) * this.fitrahRate },
        get result() { return this.tab === 'penghasilan' ? this.penghasilan : this.tab === 'maal' ? this.maal : this.fitrah },
        rp(n) { return 'Rp ' + Math.round(n).toLocaleString('id-ID') }
    }">
    <div class="flex gap-2 rounded-full bg-white p-1.5 shadow-sm" role="tablist">
        @foreach(['penghasilan' => 'Penghasilan', 'maal' => 'Maal (harta)', 'fitrah' => 'Fitrah'] as $k => $v)
            <button type="button" role="tab" @click="tab='{{ $k }}'" :aria-selected="tab==='{{ $k }}'"
                    :class="tab==='{{ $k }}' ? 'bg-brand-800 text-white' : 'text-brand-700 hover:bg-brand-50'"
                    class="flex-1 rounded-full px-3 py-2.5 text-sm font-semibold transition">{{ $v }}</button>
        @endforeach
    </div>

    <div class="mt-6 rounded-2xl border border-brand-100 bg-white p-6">
        <div x-show="tab==='penghasilan'" class="space-y-4">
            <label class="block text-sm font-semibold">Penghasilan per bulan (Rp)<input type="number" min="0" x-model.number="income" class="mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 font-normal"></label>
            <label class="block text-sm font-semibold">Penghasilan lain per bulan (Rp)<input type="number" min="0" x-model.number="other" class="mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 font-normal"></label>
            <p class="text-sm text-brand-600">Wajib zakat jika penghasilan sebulan mencapai <strong x-text="rp(nisab/12)"></strong> (seperdua belas nisab). Tarif 2,5%.</p>
        </div>
        <div x-show="tab==='maal'" x-cloak class="space-y-4">
            <label class="block text-sm font-semibold">Tabungan, deposito, dan uang tunai (Rp)<input type="number" min="0" x-model.number="savings" class="mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 font-normal"></label>
            <label class="block text-sm font-semibold">Nilai emas dan perak (Rp)<input type="number" min="0" x-model.number="goldAssets" class="mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 font-normal"></label>
            <label class="block text-sm font-semibold">Aset lain: kendaraan usaha, saham, piutang lancar (Rp)<input type="number" min="0" x-model.number="assets" class="mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 font-normal"></label>
            <label class="block text-sm font-semibold">Utang jatuh tempo (Rp)<input type="number" min="0" x-model.number="debt" class="mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 font-normal"></label>
            <p class="text-sm text-brand-600">Wajib zakat jika harta bersih telah mencapai nisab <strong x-text="rp(nisab)"></strong> (setara 85 gram emas) dan dimiliki selama satu tahun (haul). Tarif 2,5%.</p>
        </div>
        <div x-show="tab==='fitrah'" x-cloak class="space-y-4">
            <label class="block text-sm font-semibold">Jumlah jiwa yang ditanggung<input type="number" min="1" x-model.number="people" class="mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 font-normal"></label>
            <p class="text-sm text-brand-600">Zakat fitrah per jiwa <strong x-text="rp(fitrahRate)"></strong> (setara 2,5 kg atau 3,5 liter makanan pokok). Waktu terbaik: sebelum salat Id.</p>
        </div>
    </div>

    <div class="mt-6 rounded-2xl bg-brand-800 p-6 text-white">
        <p class="text-sm text-brand-200">Zakat yang perlu ditunaikan</p>
        <p class="mt-1 font-display text-4xl text-leaf-400" x-text="rp(result)"></p>
        <p class="mt-2 text-sm text-brand-200" x-show="result === 0 && tab !== 'fitrah'">Belum mencapai nisab, sehingga belum wajib zakat. Anda tetap bisa berinfak atau bersedekah.</p>
        <div class="mt-5 flex flex-wrap gap-3">
            <a :href="'{{ route('donation.create') }}?type=zakat&amp;amount=' + result" :class="result < 10000 && 'pointer-events-none opacity-50'" class="rounded-full bg-leaf-400 px-6 py-3 text-sm font-bold text-brand-950 hover:bg-leaf-300">Tunaikan zakat ini</a>
            <a href="{{ route('donation.create', ['type' => 'infak']) }}" class="rounded-full border border-brand-400 px-6 py-3 text-sm font-semibold hover:bg-brand-700">Berinfak</a>
        </div>
    </div>
    <p class="mt-4 text-xs leading-relaxed text-brand-600">Hasil ini perkiraan. Harga emas dan tarif fitrah diatur oleh admin dan perlu disesuaikan dengan ketetapan BAZNAS atau fatwa setempat. Untuk kasus yang rumit, konsultasikan dengan ustaz atau lembaga amil zakat.</p>
</section>
@endsection
