<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
@props(['config'])
<x-text-field :config="$config" {{ $attributes }}>
<x-slot name="append" class="flex items-center">{{ $append }}</x-slot>
<x-slot name="after" >{{ $after }}</x-slot>
{{ $slot ?? "" }}
</x-text-field>