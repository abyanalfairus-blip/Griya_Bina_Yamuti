@props(['value' => 0])
<div class="h-2.5 w-full overflow-hidden rounded-full bg-brand-100" role="progressbar" aria-valuenow="{{ $value }}" aria-valuemin="0" aria-valuemax="100">
    <div class="h-full rounded-full bg-gradient-to-r from-brand-500 to-leaf-400" style="width: {{ max(2, $value) }}%"></div>
</div>
