<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use WireUi\Traits\WireUiActions;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;


new #[Layout('layouts.app', ['title' => 'User Profile'])] class extends Component {
    use WithFileUploads, WireUiActions;

    public $user;
    public $name;
    public $email;
    public $photo;

    // Password fields
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $this->user = auth()->user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
        ]);

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->notification()->success('Profile Updated', 'Your basic information has been saved.');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $this->user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->notification()->success('Password Changed', 'Security credentials updated successfully.');
    }

    public function updatedPhoto()
    {
        $this->validate(['photo' => 'image|max:1024']); // 1MB Max

        $path = $this->photo->store('profile-photos', 'public');
        $this->user->update(['profile_photo_path' => $path]);

        $this->notification()->info('Photo Uploaded', 'Your profile picture has been updated.');
    }
}; ?>

<div class="p-6 lg:p-10 max-w-7xl min-h-screen bg-base-100">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold tracking-tight">Account Settings</h1>
        <p class="text-base-content/60 mt-1">Manage your public profile, security, and preferences.</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-10">
        <aside class="w-full lg:w-64 shrink-0 lg:sticky lg:top-24 h-fit">
            <nav class="flex flex-col gap-1">
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/10 text-primary font-bold transition-all">
                    <x-icon name="user" class="w-5 h-5" />
                    General
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-base-200 transition-all text-base-content/70">
                    <x-icon name="shield-check" class="w-5 h-5" />
                    Security
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-base-200 transition-all text-base-content/70">
                    <x-icon name="bell" class="w-5 h-5" />
                    Notifications
                </a>
            </nav>
        </aside>

        <main class="flex-1 space-y-8">

            <div class="card bg-base-100 border border-base-200 shadow-sm overflow-hidden">
                <div class="card-body flex-row items-center gap-6">
                    <div class="relative group">
                        <x-avatar size="32"
                            src="{{ $user->profile_photo_path ? asset('storage/'.$user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($name) }}"
                            class="ring-4 ring-base-200 group-hover:ring-primary/50 transition-all"
                        />
                        <label class="absolute inset-0 flex items-center justify-center bg-black/40 rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-all">
                            <x-icon name="camera" class="w-6 h-6 text-white" />
                            <input type="file" wire:model="photo" class="hidden" />
                        </label>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold">Profile Picture</h3>
                        <p class="text-xs text-base-content/50 mb-3">JPG, GIF or PNG. Max size of 2MB</p>
                        <x-button sm outline label="Change Photo" icon="arrow-up-on-square-stack" mini x-on:click="$el.closest('.group').querySelector('input').click()" />
                    </div>
                </div>
            </div>

            <x-card title="Public Profile" shadow="none" class="border border-base-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input label="First Name" wire:model="first_name" placeholder="John" />
                    <x-input label="Last Name" wire:model="last_name" placeholder="Doe" />
                    <x-input label="Email Address" wire:model="email" icon="envelope" mini />
                    <x-native-select
                        label="Timezone"
                        placeholder="Select a timezone"
                        :options="['UTC', 'PST', 'EST', 'GMT']"
                        wire:model="timezone"
                    />
                </div>
                <div class="mt-6 flex justify-end gap-3 border-t border-base-200 pt-6">
                    <x-button flat label="Cancel" />
                    <x-button primary label="Save Changes" spinner="save" />
                </div>
            </x-card>

            <div class="card bg-error/5 border border-error/20">
                <div class="card-body">
                    <h3 class="text-error font-bold flex items-center gap-2">
                        <x-icon name="exclamation-triangle" class="w-5 h-5" />
                        Deactivate Account
                    </h3>
                    <p class="text-sm opacity-70">Once you delete your account, there is no going back. Please be certain.</p>
                    <div class="card-actions justify-start mt-4">
                        <x-button label="Delete Account" flat negative sm />
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
