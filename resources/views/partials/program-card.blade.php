<article class="group flex flex-col overflow-hidden rounded-2xl border border-pine-100 bg-white">
    <a href="{{ route('programs.show', $program) }}" class="block overflow-hidden">
        <x-cover :src="$program->image" :alt="$program->title" class="h-48 transition duration-500 group-hover:scale-105" />
    </a>
    <div class="flex flex-1 flex-col p-5">
        <span class="w-fit rounded-full bg-pine-50 px-2.5 py-1 text-xs font-semibold text-pine-700">{{ $program->category_label }}</span>
        <h3 class="mt-3 font-display text-xl leading-snug text-pine-900">
            <a href="{{ route('programs.show', $program) }}">{{ $program->title }}</a>
        </h3>
        <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-pine-700">{{ $program->excerpt }}</p>
        <div class="mt-auto pt-5">
            <x-progress :value="$program->progress" />
            <div class="mt-2 flex items-end justify-between text-sm">
                <div>
                    <p class="font-bold text-pine-900">{{ rupiah($program->collected) }}</p>
                    <p class="text-xs text-pine-600">terkumpul dari {{ rupiah($program->target) }}</p>
                </div>
                @if(!is_null($program->days_left))
                    <p class="text-xs font-medium text-pine-600">{{ $program->days_left }} hari lagi</p>
                @endif
            </div>
            <a href="{{ route('donation.create', ['program' => $program->id]) }}" class="mt-4 block rounded-full bg-pine-800 py-2.5 text-center text-sm font-bold text-white transition hover:bg-pine-700">Donasi untuk program ini</a>
        </div>
    </div>
</article>
