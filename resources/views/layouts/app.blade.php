<x-layouts::app.sidebar :title="$title">
    <div>
        {{ $slot }}
    </div>
    <livewire:image-viewer />
</x-layouts::app.sidebar>