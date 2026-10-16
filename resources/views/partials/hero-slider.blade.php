@php $slides = \App\Support\Pillars::slides(); @endphp
@if(count($slides))
<div class="relative h-[20rem] md:h-[26rem] lg:h-full"
     role="region" aria-roledescription="carousel" aria-label="Foto kegiatan Griya Bina Yamuti" tabindex="0"
     x-data="{
        i: 0, n: {{ count($slides) }}, timer: null, paused: false, tx: 0,
        reduce: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
        go(k) { this.i = (k + this.n) % this.n },
        next() { this.go(this.i + 1) },
        prev() { this.go(this.i - 1) },
        start() { if (this.reduce) return; clearInterval(this.timer); this.timer = setInterval(() => { if (!this.paused) this.next() }, 5000) }
     }"
     x-init="start()" @hero-prev.window="prev(); start()" @hero-next.window="next(); start()"
     @mouseenter="paused = true" @mouseleave="paused = false"
     @focusin="paused = true" @focusout="paused = false"
     @keydown.arrow-left="prev()" @keydown.arrow-right="next()"
     @touchstart.passive="tx = $event.touches[0].clientX"
     @touchend.passive="let d = $event.changedTouches[0].clientX - tx; if (Math.abs(d) > 40) { d < 0 ? next() : prev() }">

    {{-- Lapisan foto: tepinya memudar (gradasi) ke warna latar hero --}}
    <div class="hero-fade absolute inset-0">
        @foreach($slides as $k => $s)
            <figure class="absolute inset-0 transition-opacity duration-700"
                    :class="i === {{ $k }} ? 'opacity-100' : 'pointer-events-none opacity-0'"
                    :aria-hidden="i !== {{ $k }} ? 'true' : 'false'"
                    role="group" aria-roledescription="slide" aria-label="{{ $k + 1 }} dari {{ count($slides) }}">
                <img src="{{ media_url($s['img']) }}" alt="{{ $s['alt'] }}" class="h-full w-full object-cover transition-transform duration-[7000ms] ease-out" :class="i === {{ $k }} ? 'scale-105' : 'scale-100'" style="object-position: {{ $s['pos'] }}"
                     @if($k === 0) fetchpriority="high" @else loading="lazy" @endif>
            </figure>
        @endforeach
    </div>

    {{-- Keterangan dan kendali berada di luar lapisan pudar agar tetap jelas terbaca --}}
    <div class="absolute inset-x-4 bottom-4 flex flex-col items-end gap-3 text-right sm:inset-x-6 lg:right-8">
        @foreach($slides as $k => $s)
            <a x-show="i === {{ $k }}" @if($k > 0) x-cloak @endif x-transition.opacity.duration.500ms
               href="{{ route('programs.overview') }}{{ $s['program'] ? '#' . $s['program'] : '' }}"
               class="max-w-xs rounded-full bg-brand-950/70 px-4 py-2 text-sm font-semibold text-white backdrop-blur transition hover:bg-brand-950/90 md:text-base">{{ $s['caption'] }}<span aria-hidden="true"> &rarr;</span></a>
        @endforeach

        <div class="flex items-center gap-3">
            <button type="button" @click="prev()" aria-label="Foto sebelumnya"
                    class="grid h-9 lg:hidden w-9 place-items-center rounded-full bg-white/15 text-white backdrop-blur transition hover:bg-white/30">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <div class="flex items-center gap-2">
                @foreach($slides as $k => $s)
                    <button type="button" @click="go({{ $k }}); start()" aria-label="Tampilkan foto {{ $k + 1 }}"
                            :class="i === {{ $k }} ? 'w-6 bg-leaf-400' : 'w-2 bg-white/60 hover:bg-white'"
                            class="h-2 rounded-full transition-all"></button>
                @endforeach
            </div>
            <button type="button" @click="next()" aria-label="Foto berikutnya"
                    class="grid h-9 lg:hidden w-9 place-items-center rounded-full bg-white/15 text-white backdrop-blur transition hover:bg-white/30">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </button>
        </div>
    </div>
</div>
@endif
