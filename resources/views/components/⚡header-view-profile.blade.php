<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div class="sm:hidden flex items-center justify-between p-4">
    <button @click="open = !open" class="p-2 rounded hover:bg-base-200 hover:cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
            <path fill-rule="evenodd" d="M2 6.75A.75.75 0 0 1 2.75 6h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 6.75Zm0 6.5a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
        </svg>
    </button>
    <div>
        <div
            x-data="{ open: false }"
            class=""
            >    
            <div @click="open = !open" class="flex items-center p-1 rounded-lg  justify-between space-x-2 flex-shrink-0 whitespace-nowrap text-sm z-150 tooltip tooltip-left w-15" data-tip="User Profile">
                <img class="rounded-full size-7 shadow-lg" src="https://img.daisyui.com/images/profile/demo/distracted1@192.webp" />
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>

            </div>
            <div x-show="open" x-cloak @click.away="open = false" class="w-40 absolute right-4 top-15 border border-base-200 shadow-2xl p-2 rounded-lg flex flex-col space-y-2">
                <livewire:nav-link 
                    url="admin.user-profile" 
                    label="User Profile" 
                    icon='<path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-5.5-2.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0ZM10 12a5.99 5.99 0 0 0-4.793 2.39A6.483 6.483 0 0 0 10 16.5a6.483 6.483 0 0 0 4.793-2.11A5.99 5.99 0 0 0 10 12Z" clip-rule="evenodd"/>'
                    :forceShowLabel="true"
                />
                <livewire:nav-link 
                    url="#" 
                    label="Logout" 
                    icon='<path fill-rule="evenodd" d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25Z" clip-rule="evenodd" /><path fill-rule="evenodd" d="M6 10a.75.75 0 0 1 .75-.75h9.546l-1.048-.943a.75.75 0 1 1 1.004-1.114l2.5 2.25a.75.75 0 0 1 0 1.114l-2.5 2.25a.75.75 0 1 1-1.004-1.114l1.048-.943H6.75A.75.75 0 0 1 6 10Z" clip-rule="evenodd" />'                                 
                    :forceShowLabel="true"
                    :class="'border-t border-base-200 mt-2 pt-2 rounded-none'"
                />
            </div>
        </div>
    </div>
</div>