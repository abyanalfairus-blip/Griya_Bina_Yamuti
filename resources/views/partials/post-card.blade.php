<article class="group flex flex-col overflow-hidden rounded-2xl border border-pine-100 bg-white">
    <a href="{{ route('posts.show', $post) }}" class="block overflow-hidden">
        <x-cover :src="$post->image" :alt="$post->title" class="h-44 transition duration-500 group-hover:scale-105" />
    </a>
    <div class="flex flex-1 flex-col p-5">
        <p class="text-xs font-medium text-pine-600">{{ $post->category_label }}, {{ optional($post->published_at)->translatedFormat('d F Y') }}</p>
        <h3 class="mt-2 font-display text-lg leading-snug text-pine-900"><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h3>
        <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-pine-700">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 140) }}</p>
    </div>
</article>
