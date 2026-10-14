<?php

namespace App\Http\Controllers\Admin;

use App\Models\Message;

class MessageController extends ResourceController
{
    protected string $model = Message::class;
    protected string $route = 'messages';
    protected string $title = 'Pesan Masuk';
    protected string $singular = 'pesan';
    protected array $actions = ['delete'];

    protected function columns(): array
    {
        return [
            ['name', 'Pengirim', 'text'],
            ['contact', 'Kontak', 'text'],
            ['subject', 'Subjek', 'text'],
            ['body', 'Pesan', 'long'],
            ['created_at', 'Diterima', 'date'],
        ];
    }

    public function index()
    {
        Message::where('is_read', false)->update(['is_read' => true]);
        $items = Message::latest()->paginate(15);
        $items->getCollection()->transform(function ($m) {
            $m->contact = trim(($m->email ?? '') . ' ' . ($m->phone ?? ''));
            return $m;
        });
        return view('admin.resource.index', $this->viewData() + ['items' => $items, 'columns' => $this->columns()]);
    }
}
