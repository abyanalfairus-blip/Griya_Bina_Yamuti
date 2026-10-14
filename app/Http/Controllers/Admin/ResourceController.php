<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Pengendali CRUD generik untuk panel admin.
 * Subclass cukup mendefinisikan model, rute, kolom tabel, dan field formulir.
 */
abstract class ResourceController extends Controller
{
    /** @var class-string<Model> */
    protected string $model;
    protected string $route;      // contoh: programs
    protected string $title;      // contoh: Program Donasi
    protected string $singular;   // contoh: program
    protected string $orderBy = 'id';
    protected string $orderDir = 'desc';
    protected array $actions = ['create', 'edit', 'delete'];

    /** Kolom tabel: [['key', 'label', 'type']] type: text|money|bool|image|date|badge|long */
    abstract protected function columns(): array;

    /** Field formulir: nama => [label, type, rules, options?, help?] */
    protected function fields(): array
    {
        return [];
    }

    protected function viewData(): array
    {
        return [
            'route' => $this->route,
            'title' => $this->title,
            'singular' => $this->singular,
            'actions' => $this->actions,
        ];
    }

    public function index()
    {
        $items = $this->model::query()->orderBy($this->orderBy, $this->orderDir)->paginate(15);
        return view('admin.resource.index', $this->viewData() + ['items' => $items, 'columns' => $this->columns()]);
    }

    public function create()
    {
        return view('admin.resource.form', $this->viewData() + ['item' => new $this->model, 'fields' => $this->fields()]);
    }

    public function store(Request $request)
    {
        $data = $this->payload($request, null);
        $this->model::create($this->beforeSave($data, null));
        return redirect()->route('admin.' . $this->route . '.index')->with('success', ucfirst($this->singular) . ' berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = $this->model::findOrFail($id);
        return view('admin.resource.form', $this->viewData() + ['item' => $item, 'fields' => $this->fields()]);
    }

    public function update(Request $request, $id)
    {
        $item = $this->model::findOrFail($id);
        $data = $this->payload($request, $item);
        $item->update($this->beforeSave($data, $item));
        return redirect()->route('admin.' . $this->route . '.index')->with('success', ucfirst($this->singular) . ' berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = $this->model::findOrFail($id);
        foreach ($this->fields() as $name => $f) {
            if (in_array($f[1] ?? '', ['image', 'file']) && $item->{$name}) {
                Storage::disk('public')->delete($item->{$name});
            }
        }
        $item->delete();
        return redirect()->route('admin.' . $this->route . '.index')->with('success', ucfirst($this->singular) . ' dihapus.');
    }

    /** Titik masuk untuk menyesuaikan data sebelum disimpan. */
    protected function beforeSave(array $data, ?Model $item): array
    {
        return $data;
    }

    private function payload(Request $request, ?Model $item): array
    {
        $fields = $this->fields();
        $rules = [];
        foreach ($fields as $name => $f) {
            $rules[$name] = $f[2] ?? 'nullable';
        }
        $request->validate($rules);

        $data = [];
        foreach ($fields as $name => $f) {
            $type = $f[1];
            if (in_array($type, ['image', 'file'])) {
                if ($request->hasFile($name)) {
                    if ($item && $item->{$name}) {
                        Storage::disk('public')->delete($item->{$name});
                    }
                    $data[$name] = $request->file($name)->store('uploads/' . $this->route, 'public');
                }
            } elseif ($type === 'checkbox') {
                $data[$name] = $request->boolean($name);
            } elseif ($type === 'number') {
                $data[$name] = (int) $request->input($name, 0);
            } else {
                $data[$name] = $request->input($name);
            }
        }
        return $data;
    }
}
