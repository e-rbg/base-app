<?php

use Livewire\Component;

new class extends Component
{
    // Define the property to accept data from parents
    public array $items = [];

    // Optional: Set a default "Home" item if none provided
    public function mount(array $items = [])
    {
        $this->items = $items ?: [['url' => '/', 'label' => 'Home']];
    }
};
?>

<div class="breadcrumbs text-xs">
    @php $totalItems = count($items); @endphp
    <ul>
        @foreach ($items as $item)
            <li>
                @if ($loop->last || $totalItems === 1 || empty($item['url']))
                    <span class="text-base-content/60" @if($loop->last) aria-current="page"@endif>{{ $item['label'] }}</span>
                @else
                    <a href="{{ $item['url'] ?? '#' }}">{{ $item['label'] }}</a>
                @endif
            </li>
        @endforeach
    </ul>
</div>


<!-- Usage Example: -->
{{--
    <livewire:breadcrumbs :items="[
        ['label' => 'Home', 'url' => route('dashboard')],
        ['label' => 'Documents', 'url' => route('documents.index')],
        ['label' => 'Add Document'] {{-- No URL needed for the last item
    ]"/> 
--}}
