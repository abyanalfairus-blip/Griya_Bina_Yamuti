@props(['src' => null, 'alt' => ''])
@if($src)
    <img src="{{ media_url($src) }}" alt="{{ $alt }}" loading="lazy" {{ $attributes->merge(['class' => 'w-full object-cover']) }}>
@else
    <div role="img" aria-label="{{ $alt }}" {{ $attributes->merge(['class' => 'grid w-full place-items-center bg-brand-100 text-brand-300']) }}>
        <svg viewBox="0 0 24 24" class="h-12 w-12" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11.5 12 4l9 7.5"/><path d="M5.5 10v9.5h13V10"/></svg>
    </div>
@endif
