@php $slides = \App\Support\Pillars::slides(); @endphp
@if(count($slides))
<div class="relative h-[24rem] overflow-hidden rounded-3xl bg-brand-800 shadow-2xl shadow-black/30 ring-1 ring-white/10 md:h-[30rem]"
     role="region" aria-roledescription="carousel" aria-label="Foto kegiatan Griya Bina Yamuti" tabindex="0"
     x-data="{
        i: 0, n: {{ count($slides) }}, timer: null, paused: false, tx: 0,
        reduce: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
        go(k) { this.i = (k + this.n) % this.n },
        next() { this.go(this.i + 1) },
        prev() { this.go(this.i - 1) },
        start() { if (this.reduce) return; clearInterval(this.timer); this.timer = setInterval(() => { if (!this.paused) this.next() }, 5000) }
     }"
     x-init="start()"
     @mouseenter="paused = true" @mouseleave="paused = false"
     @focusin="paused = true" @focusout="paused = false"
     @keydown.arrow-left="prev()" @keydown.arrow-right="next()"
     @touchstart.passive="tx = $event.touches[0].clientX"
     @touchend.passive="let d = $event.changedTouches[0].clientX - tx; if (Math.abs(d) > 40) { d < 0 ? next() : prev() }">

    @foreach($slides as $k => $s)
        <figure class="absolute inset-0 transition-opacity duration-700"
                :class="i === {{ $k }} ? 'opacity-100' : 'pointer-events-none opacity-0'"
                :aria-hidden="i !== {{ $k }} ? 'true' : 'false'"
                role="group" aria-roledescription="slide" aria-label="{{ $k + 1 }} dari {{ count($slides) }}">
            <img src="{{ media_url($s['img']) }}" alt="{{ $s['alt'] }}" class="h-full w-full object-cover" style="object-position: {{ $s['pos'] }}"
                 @if($k === 0) fetchpriority="high" @else loading="lazy" @endif>
            <figcaption class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent px-5 pb-12 pt-20">
                <a href="{{ route('programs.overview') }}{{ $s['program'] ? '#' . $s['program'] : '' }}" class="text-base font-semibold text-white underline-offset-4 hover:underline md:text-lg">{{ $s['caption'] }}</a>
            </figcaption>
        </figure>
    @endforeach

    <button type="button" @click="prev()" aria-label="Foto sebelumnya"
            class="absolute left-3 top-1/2 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-black/40 text-white backdrop-blur transition hover:bg-black/60">
        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
    </button>
    <button type="button" @click="next()" aria-label="Foto berikutnya"
            class="absolute right-3 top-1/2 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-black/40 text-white backdrop-blur transition hover:bg-black/60">
        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
    </button>

    <div class="absolute inset-x-0 bottom-4 flex justify-center gap-2">
        @foreach($slides as $k => $s)
            <button type="button" @click="go({{ $k }}); start()" aria-label="Tampilkan foto {{ $k + 1 }}"
                    :class="i === {{ $k }} ? 'w-6 bg-leaf-400' : 'w-2 bg-white/60 hover:bg-white'"
                    class="h-2 rounded-full transition-all"></button>
        @endforeach
    </div>
</div>
@endif
