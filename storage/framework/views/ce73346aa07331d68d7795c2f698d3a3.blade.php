<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>

<x-card  {{ $attributes }}>
<x-slot name="action" >{{ $action }}</x-slot>
<x-slot name="footer" class="flex items-center justify-end gap-x-3">{{ $footer }}</x-slot>
{{ $slot ?? "" }}
</x-card>