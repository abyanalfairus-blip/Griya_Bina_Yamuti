<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::published()
            ->when($request->filled('kategori'), fn ($q) => $q->where('category', $request->kategori))
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($w) => $w->where('title', 'like', '%' . $request->q . '%')->orWhere('excerpt', 'like', '%' . $request->q . '%')))
            ->latest('published_at')->paginate(9)->withQueryString();

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        abort_unless($post->is_published, 404);

        return view('posts.show', [
            'post' => $post,
            'related' => Post::published()->where('id', '!=', $post->id)->where('category', $post->category)->latest('published_at')->take(3)->get(),
        ]);
    }
}
