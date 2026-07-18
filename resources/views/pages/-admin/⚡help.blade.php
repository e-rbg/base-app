<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.app')] #[Title('Help')] class extends Component
{
    //
};
?>

<x-main-container 
    title="Help" 
    :breadcrumbs="[
        ['url' => route('admin.dashboard'), 'label' => 'Dashboard'],
        ['label' => 'Help']
    ]"
>
    <div class="max-w-full border border-primary p-6">
        <p>Help content goes here.</p>
    </div>
</x-main-container>
