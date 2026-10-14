@extends('admin.layout')
@section('title', 'Pengaturan situs')
@section('heading', 'Pengaturan situs')
@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" class="max-w-3xl space-y-8">
    @csrf @method('PUT')
    @foreach($groups as $group => $fields)
        <section class="rounded-2xl border border-pine-100 bg-white p-6">
            <h2 class="font-display text-lg text-pine-900">{{ $group }}</h2>
            <div class="mt-4 space-y-4">
                @foreach($fields as $key => [$label, $type])
                    <label class="block text-sm font-semibold">{{ $label }}
                        @if($type === 'textarea')
                            <textarea name="{{ $key }}" rows="3" class="mt-1.5 w-full rounded-xl border border-pine-200 px-4 py-3 text-sm font-normal">{{ old($key, setting($key)) }}</textarea>
                        @else
                            <input type="{{ $type }}" name="{{ $key }}" value="{{ old($key, setting($key)) }}" class="mt-1.5 w-full rounded-xl border border-pine-200 px-4 py-3 text-sm font-normal">
                        @endif
                    </label>
                @endforeach
            </div>
        </section>
    @endforeach
    <button class="rounded-full bg-pine-800 px-8 py-3 text-sm font-bold text-white hover:bg-pine-700">Simpan pengaturan</button>
</form>
@endsection
