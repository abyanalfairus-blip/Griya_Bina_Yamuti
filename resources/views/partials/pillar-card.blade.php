<a href="{{ route('programs.overview') }}#{{ $pr['slug'] }}" class="group flex flex-col overflow-hidden rounded-2xl border border-brand-100 bg-white transition hover:-translate-y-0.5 hover:shadow-lg">
    @if(!empty($pr['img']))
        <img src="{{ media_url($pr['img']) }}" alt="{{ $pr['alt'] ?? $pr['title'] }}" loading="lazy" class="aspect-[16/10] w-full object-cover" style="object-position: {{ $pr['pos'] ?? 'center' }}">
    @else
        <div class="relative grid aspect-[16/10] place-items-center overflow-hidden bg-brand-800">
            <div class="star-pattern absolute inset-0"></div>
            <x-icon :name="$pr['icon']" class="relative h-14 w-14 text-leaf-400" />
        </div>
    @endif
    <div class="flex flex-1 flex-col p-5">
        <p class="text-xs font-semibold uppercase tracking-wide text-brand-500">{{ $pr['focus'] }}</p>
        <h3 class="mt-1 font-display text-xl text-brand-900">{{ $pr['title'] }}</h3>
        @if($pr['tagline'])<p class="mt-2 text-sm leading-relaxed text-brand-700">{{ $pr['tagline'] }}</p>@endif
        <span class="mt-4 text-sm font-bold text-brand-800 group-hover:underline">Lihat program turunan &rarr;</span>
    </div>
</a>
