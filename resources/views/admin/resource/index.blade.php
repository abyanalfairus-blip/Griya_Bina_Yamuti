@extends('admin.layout')
@section('title', $title)
@section('heading', $title)
@section('content')
<div class="mb-5 flex items-center justify-between">
    <p class="text-sm text-pine-600">{{ $items->total() }} data</p>
    @if(in_array('create', $actions))
        <a href="{{ route('admin.' . $route . '.create') }}" class="rounded-full bg-pine-800 px-5 py-2.5 text-sm font-bold text-white hover:bg-pine-700">Tambah {{ $singular }}</a>
    @endif
</div>

<div class="overflow-x-auto rounded-2xl border border-pine-100 bg-white">
    <table class="w-full text-left text-sm">
        <thead class="bg-pine-50 text-pine-700">
            <tr>
                @foreach($columns as [$key, $label, $type])<th class="px-4 py-3 font-semibold">{{ $label }}</th>@endforeach
                <th class="px-4 py-3 text-right font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-pine-50">
            @forelse($items as $item)
                <tr class="align-top">
                    @foreach($columns as [$key, $label, $type])
                        @php $v = $item->{$key}; @endphp
                        <td class="px-4 py-3">
                            @switch($type)
                                @case('image')
                                    @if($v)<img src="{{ \Illuminate\Support\Facades\Storage::url($v) }}" alt="" class="h-12 w-16 rounded-lg object-cover">@else<span class="text-pine-300">-</span>@endif
                                    @break
                                @case('money') {{ rupiah($v) }} @break
                                @case('bool')
                                    <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $v ? 'bg-pine-100 text-pine-800' : 'bg-gray-100 text-gray-600' }}">{{ $v ? 'Ya' : 'Tidak' }}</span>
                                    @break
                                @case('date') {{ $v ? \Illuminate\Support\Carbon::parse($v)->translatedFormat('d M Y') : '-' }} @break
                                @case('badge') <span class="rounded-full bg-pine-50 px-2.5 py-1 text-xs font-semibold text-pine-700">{{ \App\Models\PaymentMethod::TYPES[$v] ?? $v }}</span> @break
                                @case('long') <p class="max-w-md whitespace-pre-line text-pine-800">{{ $v }}</p> @break
                                @default <span class="{{ $loop->first ? 'font-semibold text-pine-900' : '' }}">{{ $v }}</span>
                            @endswitch
                        </td>
                    @endforeach
                    <td class="whitespace-nowrap px-4 py-3 text-right">
                        @if(in_array('edit', $actions))
                            <a href="{{ route('admin.' . $route . '.edit', $item->id) }}" class="font-semibold text-pine-700 hover:underline">Ubah</a>
                        @endif
                        @if(in_array('delete', $actions))
                            <form method="POST" action="{{ route('admin.' . $route . '.destroy', $item->id) }}" class="ml-3 inline" onsubmit="return confirm('Hapus data ini? Tindakan tidak dapat dibatalkan.')">
                                @csrf @method('DELETE')<button class="font-semibold text-red-700 hover:underline">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="{{ count($columns) + 1 }}" class="px-4 py-10 text-center text-pine-600">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $items->links() }}</div>
@endsection
