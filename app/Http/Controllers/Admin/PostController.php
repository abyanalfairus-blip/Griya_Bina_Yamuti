<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

class PostController extends ResourceController
{
    protected string $model = Post::class;
    protected string $route = 'posts';
    protected string $title = 'Kabar & Literasi';
    protected string $singular = 'tulisan';

    protected function columns(): array
    {
        return [
            ['image', 'Gambar', 'image'],
            ['title', 'Judul', 'text'],
            ['category_label', 'Kategori', 'badge'],
            ['published_at', 'Terbit', 'date'],
            ['is_published', 'Terbit?', 'bool'],
        ];
    }

    protected function fields(): array
    {
        return [
            'title' => ['Judul', 'text', 'required|string|max:200'],
            'category' => ['Kategori', 'select', 'required|in:' . implode(',', array_keys(Post::CATEGORIES)), Post::CATEGORIES],
            'excerpt' => ['Ringkasan', 'textarea', 'nullable|string|max:300'],
            'body' => ['Isi tulisan', 'textarea-lg', 'required|string', null, 'Mendukung format Markdown: **tebal**, *miring*, daftar dengan tanda -, judul dengan ##.'],
            'image' => ['Gambar sampul', 'image', 'nullable|image|max:3072'],
            'published_at' => ['Tanggal terbit', 'datetime', 'nullable|date'],
            'is_published' => ['Terbitkan', 'checkbox', 'boolean'],
        ];
    }

    protected function beforeSave(array $data, ?Model $item): array
    {
        $data['published_at'] = $data['published_at'] ?: ($item?->published_at ?? now());
        if (! $item) {
            $data['user_id'] = auth()->id();
        }
        return $data;
    }
}
