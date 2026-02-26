<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use WireUi\Traits\WireUiActions;

new class extends Component {
    use WithFileUploads, WireUiActions;

    public $user;
    public $name;
    public $email;
    public $photo;

    // Password State
    public $current_password;
    public $password;
    public $password_confirmation;

    public function mount()
    {
        $this->user = auth()->user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
    }

    public function saveInfo()
    {
        $this->validate([
            'name'  => 'required|min:3',
            'email' => "required|email|unique:users,email,{$this->user->id}",
        ]);

        $this->user->update([
            'name'  => $this->name,
            'email' => $this->email,
        ]);

        $this->notification()->success('Profile Updated', 'Your information has been saved.');
    }

    public function updatedPhoto()
    {
        $this->validate(['photo' => 'image|max:2048']); // 2MB Max

        $path = $this->photo->store('avatars', 'public');
        $this->user->update(['profile_photo_path' => $path]);

        $this->notification()->info('Avatar Updated', 'Your new profile picture is live.');
    }

    public function changePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        $this->user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        $this->notification()->success('Success', 'Password changed successfully.');
    }
}; ?>

<div class="p-4 sm:p-10 max-w-5xl mx-auto space-y-10">
    <div class="border-b border-base-200 pb-5">
        <h1 class="text-3xl font-black text-base-content">Account Settings</h1>
        <p class="text-base-content/60">Manage your public profile and security credentials.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        <div class="space-y-6">
            <x-card title="Profile Picture">
                <div class="flex flex-col items-center">
                    <div class="relative group cursor-pointer">
                        <x-avatar size="32"
                            src="{{ $photo ? $photo->temporaryUrl() : ($user->profile_photo_path ? asset('storage/'.$user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($name)) }}"
                            class="ring-4 ring-primary/20"
                        />
                        <label class="absolute inset-0 flex items-center justify-center bg-black/40 rounded-full opacity-0 group-hover:opacity-100 transition-all">
                            <x-icon name="camera" class="w-8 h-8 text-white" />
                            <input type="file" wire:model="photo" class="hidden" />
                        </label>
                    </div>
                    <div wire:loading wire:target="photo" class="mt-4">
                        <x-badge flat primary label="Uploading..." />
                    </div>
                </div>
            </x-card>

            <div class="stats stats-vertical shadow w-full bg-base-100 border border-base-200">
                <div class="stat">
                    <div class="stat-title">Joined</div>
                    <div class="stat-value text-lg">{{ $user->created_at->format('M d, Y') }}</div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-8">

            <x-card title="Personal Information" shadow="none" class="border border-base-200">
<form wire:submit="saveInfo" class="space-y-4">
