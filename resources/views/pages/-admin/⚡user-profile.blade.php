<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyNewEmail;

new #[Layout('layouts.app', ['title' => 'User Profile'])] class extends Component {
    use WithFileUploads, WireUiActions;

    public User $user;
    
    // Form States
    public string $first_name = '';
    public string $middle_name = '';
    public string $last_name = '';
    public string $username = '';
    public string $email = '';
    public string $timezone = 'UTC';
    public $photo; // For temporary file upload
    public ?string $enteredCode = null;

    public function mount()
    {
        $this->user = auth()->user()->load('profile');
        
        $this->first_name  = $this->user->profile->first_name;
        $this->middle_name = $this->user->profile->middle_name ?? '';
        $this->last_name   = $this->user->profile->last_name;
        $this->timezone    = $this->user->profile->timezone;
        $this->username    = $this->user->username ?? '';
        $this->email       = $this->user->email;
    }

    public function updateGeneralInfo()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'username'   => "required|alpha_dash|unique:users,username,{$this->user->id}",
            'email'      => "required|email|unique:users,email,{$this->user->id}",
            'timezone'   => 'required|string',
        ]);

        // 1. CHECK IF EMAIL IS CHANGING
        if ($this->email !== $this->user->email) {
            // Trigger the verification flow instead of saving to the main email column
            $this->requestEmailChange();
        }

        // 2. SAVE EVERYTHING ELSE
        DB::transaction(function () {
            $this->user->update([
                'username' => $this->username,
                // REMOVE 'email' => $this->email from here! 
                // We only want to save it to 'email' AFTER they verify the code.
            ]);

            $this->user->profile->update([
                'first_name'  => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name'   => $this->last_name,
                'timezone'    => $this->timezone,
            ]);
        });

        $this->notification()->success('Profile Updated', 'Changes saved.');
    }

    public function updatedPhoto()
    {
        $this->validate(['photo' => 'image|max:1024']);

        $path = $this->photo->store('avatars', 'public');
        
        // Match your schema column name 'avatar'
        $this->user->profile->update(['avatar' => $path]);

        $this->notification()->info('Avatar Changed', 'Your profile picture has been updated.');
    }

    $updateTheme = function (string $theme) {
        // 1. Update the database through the profile relationship
        $this->user->profile->update([
            'preferences->theme' => $theme,
        ]);

        // 2. Update the local state so the UI reflects it
        $this->theme = $theme;

        // 3. Dispatch to the Alpine.js listener in your layout
        $this->dispatch('theme-updated', theme: $theme);
    }

    public function requestEmailChange()
    {
        $this->validate(['email' => "required|email|unique:users,email,{$this->user->id}"]);

        $code = (string) rand(100000, 999999);

        $this->user->update([
            'unverified_email' => $this->email,
            'verification_code' => $code,
            'verification_sent_at' => now(),
        ]);

        // REAL EMAIL SENDING TRIGGERED HERE
        Mail::to($this->email)->send(new VerifyNewEmail($code));

        $this->user->refresh();
        $this->notification()->success('Email Sent', 'Please check your inbox for the verification code.');
    }

    public function confirmEmailChange() // Added '?' and default null
    {
        // 1. Guard against empty input
        if (empty($this->enteredCode)) {
            $this->notification()->warning('Input Required', 'Please enter the 6-digit code.');
            return;
        }

        if ($this->user->verification_code === $this->enteredCode) {
            DB::transaction(function () {
                $this->user->forceFill([
                    'email' => $this->user->unverified_email,
                    'unverified_email' => null,
                    'email_verified_at' => now(),
                ])->save();
                
                $this->user->refresh();
            });
            
            $this->notification()->success('Email Verified', 'Your login email has been updated.');
            $this->email = $this->user->email; // Sync the local email property
        } else {
            $this->notification()->error('Invalid Code', 'The code you entered is incorrect.');
        }
    }


}; ?>

<x-main-container 
    title="User Profile" 
    :breadcrumbs="[
        ['url' => route('admin.dashboard'), 'label' => 'Dashboard'],
        ['label' => $this->user->initialed_name . '\'s Profile']
    ]"
    wire:model.live.debounce.300ms="search"
>
    <div class="grid grid-cols-1 gap-6">
        <!-- MAIN CONTENT -->
        <div class="py-12 px-4">
            <div class="space-y-10">
                
                {{-- Header Section --}}
                <div class="flex items-center justify-between border-b border-base-200 pb-6">
                    <div>
                        <h1 class="text-3xl font-black text-base-content tracking-tight">Account Settings</h1>
                        <p class="text-base-content/50">Manage your profile and security preferences.</p>
                    </div>
                    {{-- Status indicator based on your ENUM --}}
                    <x-badge :color="$user->status === 'active' ? 'positive' : 'warning'" 
                            label="{{ strtoupper($user->status) }}" 
                            flat 
                            class="font-bold" />
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    {{-- Left: Sidebar/Avatar --}}
                    <div class="space-y-6">
                        <div class="bg-base-100 border border-base-200 rounded-[2.5rem] p-8 text-center shadow-sm">
                            <div class="relative inline-block group">
                                <x-avatar size="w-32 h-32" rounded="3xl"
                                    src="{{ $user->profile->avatar ? asset('storage/'.$user->profile->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->initialed_name).'&background=random' }}"
                                    class="ring-8 ring-base-200 group-hover:ring-primary/20 transition-all duration-500"
                                />
                                <label class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-3xl opacity-0 group-hover:opacity-100 cursor-pointer transition-all">
                                    <x-icon name="photo" class="w-8 h-8 text-white" />
                                    <input type="file" wire:model="photo" class="hidden" />
                                </label>
                            </div>
                            <h2 class="mt-4 font-black text-xl">{{ $user->fullname }}</h2>
                            <p class="text-xs opacity-40 font-bold tracking-widest mt-1"><span>@</span>{{ $user->username }}</p>
                        </div>

                        {{-- Verification Status (from your schema email_verified_at) --}}
                        {{-- Verification Status --}}
                        <div class="bg-primary/5 rounded-3xl p-6 border border-primary/10">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-xs font-black uppercase tracking-widest text-primary">Account Security</h4>
                                
                                {{-- Status Badge --}}
                                @if($user->email_verified_at)
                                    <div class="flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">
                                        <x-icon name="check-badge" mini class="w-3.5 h-3.5" />
                                        <span class="text-[10px] font-bold uppercase tracking-wider">Verified</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-600 border border-amber-500/20">
                                        <x-icon name="exclamation-circle" class="w-3.5 h-3.5" />
                                        <span class="text-[10px] font-bold uppercase tracking-wider">Unverified</span>
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-4">
                                {{-- <x-input label="Email Address" icon="envelope" wire:model="email" /> --}}

                                {{-- Show this ONLY if there is a pending change --}}
                                @if($user->unverified_email)
                                    <div class="bg-primary/5 border border-primary/20 rounded-2xl p-4 animate-pulse-slow">
                                        <div class="flex items-start gap-3">
                                            <x-icon name="information-circle" class="w-5 h-5 text-primary mt-0.5" />
                                            <div class="flex-1">
                                                <p class="text-xs font-bold text-primary uppercase tracking-widest">Action Required</p>
                                                <p class="text-sm text-base-content/70 mt-1">
                                                    We sent a code to <span class="font-bold text-base-content">{{ $user->unverified_email }}</span>. 
                                                    Enter it below to confirm your new email.
                                                </p>
                                                
                                                <div class="mt-4 flex items-end gap-2">
                                                    <x-input 
                                                        placeholder="000000" 
                                                        wire:model="enteredCode" 
                                                        x-mask="999999"
                                                        class="text-center tracking-[0.5em] font-mono text-lg"
                                                    />
                                                    <x-button 
                                                        primary 
                                                        label="Verify Code" 
                                                        wire:click="confirmEmailChange" 
                                                        spinner="confirmEmailChange" 
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Right: Profile Form --}}
                    <div class="lg:col-span-2 space-y-6">
                        <x-card title="Personal Information" rounded="3xl" shadow="none" class="border-base-200 bg-base-100 dark:bg-base-300">
                            <form wire:submit="updateGeneralInfo" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <x-input label="First Name" wire:model="first_name" />
                                    <x-input label="Last Name" wire:model="last_name" />
                                </div>
                                
                                <x-input label="Middle Name (Optional)" wire:model="middle_name" />

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-base-200 pt-6 mt-6">
                                    <x-input label="Username" prefix="@" wire:model="username" />
                                    <x-input label="Email Address" icon="envelope" wire:model="email" />
                                </div>

                                <x-select
                                    label="Timezone"
                                    placeholder="Select Timezone"
                                    wire:model="timezone"
                                    :options="['UTC', 'PST', 'Asia/Manila', 'EST', 'GMT']"
                                />

                                <div class="flex justify-end pt-4">
                                    <x-button type="submit" primary label="Save All Changes" spinner="updateGeneralInfo" class="rounded-xl px-10" />
                                </div>
                            </form>
                        </x-card>

                        {{-- Theme Preferences (using the JSON column) --}}
                        <x-card title="Preferences" rounded="3xl" shadow="none" class="border-base-200">
                            <div class="flex items-center gap-4">
                                <x-button flat secondary label="Light Mode" icon="sun" wire:click="updateTheme('light')" />
                                <x-button flat secondary label="Dark Mode" icon="moon" wire:click="updateTheme('dark')" />
                            </div>
                        </x-card>
                        <h1 class="text-2xl font-bold text-base-content tracking-tight">Danger Zone</h1>
                              
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-container>
