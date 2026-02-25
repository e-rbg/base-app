<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div class="flex">
    <!-- Desktop View Sidebar -->
    <aside
        x-data="{ collapse: $persist(false), isHovered: false }"
        x-cloak
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
        class="transition-all duration-300 sm:flex sm:flex-col justify-between relative hidden overflow-x-hidden scrollbar-hidden "
        :class="{
            'sm:w-17 cursor-expand': collapse,
            'sm:w-60': !collapse
            }"
        @click="if(collapse) collapse = false"
    >
        <!-- Brand Logo -->
        <div class="flex flex-col">
            <div class="p-4 flex items-center justify-between ">
                <div class="flex items-center space-x-2">
                    <div class="flex-shrink-0 w-8 flex justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.819" />
                        </svg>
                    </div>
                    <span x-show="!collapse" class="font-bold text-md whitespace-nowrap">Base App</span>
                </div>
                <!-- Toggle Button (shows only when hovered if closed) -->
                <button
                    x-show="!collapse || isHovered"
                    @click.stop="collapse = !collapse"
                    x-transition.opacity
                    :class="{
                        'cursor-expand right-2 w-13  border-gray-400': collapse, 
                        'cursor-collapse right-[-20px]': !collapse
                    }"
                    class="absolute flex items-center justify-center top-2 rounded tooltip tooltip-right bg-base-200 dark:bg-base-900 p-2" data-tip="Toggle Sidebar"
                >
                    <!-- Slider Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 20" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="3" rx="2" />
                        <path d="M9 3v18" />
                    </svg>
                </button>
            </div>
            <livewire:navigation />
        </div>
        <!-- Footer / Profile -->
        <div class="flex flex-col space-y-2">
            <nav x-cloak class="overflow-x-hidden flex flex-col p-2 z-50">
                <livewire:nav-link
                    url="admin.users" 
                    label="Users" 
                    icon='<path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />'
                />
                <livewire:nav-link
                    url="admin.settings" 
                    label="Settings" 
                    icon='<path fill-rule="evenodd" d="M7.84 1.804A1 1 0 0 1 8.82 1h2.36a1 1 0 0 1 .98.804l.331 1.652a6.993 6.993 0 0 1 1.929 1.115l1.598-.54a1 1 0 0 1 1.186.447l1.18 2.044a1 1 0 0 1-.205 1.251l-1.267 1.113a7.047 7.047 0 0 1 0 2.228l1.267 1.113a1 1 0 0 1 .206 1.25l-1.18 2.045a1 1 0 0 1-1.187.447l-1.598-.54a6.993 6.993 0 0 1-1.929 1.115l-.33 1.652a1 1 0 0 1-.98.804H8.82a1 1 0 0 1-.98-.804l-.331-1.652a6.993 6.993 0 0 1-1.929-1.115l-1.598.54a1 1 0 0 1-1.186-.447l-1.18-2.044a1 1 0 0 1 .205-1.251l1.267-1.114a7.05 7.05 0 0 1 0-2.227L1.821 7.773a1 1 0 0 1-.206-1.25l1.18-2.045a1 1 0 0 1 1.187-.447l1.598.54A6.992 6.992 0 0 1 7.51 3.456l.33-1.652ZM10 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd"/>'
                />
                <livewire:nav-link
                    url="admin.help" 
                    label="Help" 
                    icon='<path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" />'
                />
                
            </nav>
            <!-- User Profile Avatar -->
            <div
                x-data="{openProfile: false}"
                
                class="overflow-x-hidden flex flex-col py-2" 
                data-tip="Profile"
                @click="openProfile = !openProfile"
                @click.stop="collapse && isDesktop" 
            >
                <div class="flex items-center rounded-lg cursor-pointer">   
                    <livewire:avatar
                        label="User Profile"
                    />
                    <!-- Profie Dropdown -->
                    <livewire:profile-dropdown />
                </div>
            </div> 
        </div>
    </aside>
</div>