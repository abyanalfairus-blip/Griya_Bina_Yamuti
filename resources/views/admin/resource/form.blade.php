@extends('admin.layout')
@php $editing = $item->exists; @endphp
@section('title', ($editing ? 'Ubah ' : 'Tambah ') . $singular)
@section('heading', ($editing ? 'Ubah ' : 'Tambah ') . $singular)
@section('content')
<form method="POST" enctype="multipart/form-data" class="max-w-3xl space-y-5 rounded-2xl border border-pine-100 bg-white p-6"
      action="{{ $editing ? route('admin.' . $route . '.update', $item->id) : route('admin.' . $route . '.store') }}">
    @csrf
    @if($editing) @method('PUT') @endif

    @if($errors->any())
        <div class="rounded-xl bg-red-50 p-4 text-sm text-red-800" role="alert">
            <p class="font-semibold">Data belum bisa disimpan:</p>
            <ul class="mt-1 list-inside list-disc">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    @foreach($fields as $name => $f)
        @php
            [$label, $type] = $f;
            $options = $f[3] ?? [];
            $help = $f[4] ?? null;
            $value = old($name, $item->{$name} ?? ($type === 'checkbox' ? ! $editing : null));
            if ($type === 'date' && $value) { $value = \Illuminate\Support\Carbon::parse($value)->format('Y-m-d'); }
            if ($type === 'datetime' && $value) { $value = \Illuminate\Support\Carbon::parse($value)->format('Y-m-d\TH:i'); }
            $cls = 'mt-1.5 w-full rounded-xl border border-pine-200 px-4 py-3 text-sm font-normal focus:border-pine-500 focus:outline-none';
        @endphp
        <div>
            @if($type === 'checkbox')
                <label class="flex items-center gap-3 text-sm font-semibold">
                    <input type="checkbox" name="{{ $name }}" value="1" @checked($value) class="h-5 w-5 rounded border-pine-300 text-pine-700"> {{ $label }}
                </label>
            @else
                <label for="f-{{ $name }}" class="block text-sm font-semibold">{{ $label }}</label>
                @switch($type)
                    @case('textarea') <textarea id="f-{{ $name }}" name="{{ $name }}" rows="3" class="{{ $cls }}">{{ $value }}</textarea> @break
                    @case('textarea-lg') <textarea id="f-{{ $name }}" name="{{ $name }}" rows="14" class="{{ $cls }} font-mono">{{ $value }}</textarea> @break
                    @case('select')
                        <select id="f-{{ $name }}" name="{{ $name }}" class="{{ $cls }}">
                            @foreach($options as $k => $v)<option value="{{ $k }}" @selected((string) $value === (string) $k)>{{ $v }}</option>@endforeach
                        </select> @break
                    @case('image')
                        @if($editing && $item->{$name})<img src="{{ \Illuminate\Support\Facades\Storage::url($item->{$name}) }}" alt="" class="mt-2 h-28 rounded-lg border border-pine-100 object-cover">@endif
                        <input id="f-{{ $name }}" type="file" name="{{ $name }}" accept="image/*" class="mt-2 block w-full text-sm file:mr-4 file:rounded-full file:border-0 file:bg-pine-100 file:px-4 file:py-2 file:font-semibold file:text-pine-800"> @break
                    @case('number') <input id="f-{{ $name }}" type="number" min="0" name="{{ $name }}" value="{{ $value ?? 0 }}" class="{{ $cls }}"> @break
                    @case('date') <input id="f-{{ $name }}" type="date" name="{{ $name }}" value="{{ $value }}" class="{{ $cls }}"> @break
                    @case('datetime') <input id="f-{{ $name }}" type="datetime-local" name="{{ $name }}" value="{{ $value }}" class="{{ $cls }}"> @break
                    @default <input id="f-{{ $name }}" type="text" name="{{ $name }}" value="{{ $value }}" class="{{ $cls }}">
                @endswitch
            @endif
            @if($help)<p class="mt-1 text-xs text-pine-600">{{ $help }}</p>@endif
        </div>
    @endforeach

    <div class="flex gap-3 pt-2">
        <button class="rounded-full bg-pine-800 px-7 py-3 text-sm font-bold text-white hover:bg-pine-700">Simpan</button>
        <a href="{{ route('admin.' . $route . '.index') }}" class="rounded-full border border-pine-200 px-7 py-3 text-sm font-semibold text-pine-700 hover:bg-pine-50">Batal</a>
    </div>
</form>
@endsection
