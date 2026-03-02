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
    public string $theme = 'light'; // Added missing property
    public $photo; 
    public ?string $enteredCode = null;
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    public function mount()
    {
        $this->user = auth()->user()->load('profile');
        
        $this->first_name  = $this->user->profile->first_name;
        $this->middle_name = $this->user->profile->middle_name ?? '';
        $this->last_name   = $this->user->profile->last_name;
        $this->timezone    = $this->user->profile->timezone;
        $this->username    = $this->user->username ?? '';
        $this->email       = $this->user->email;
        $this->theme       = $this->user->profile->preferences['theme'] ?? 'light';
    }

    public function updateTheme(string $theme) // Changed from variable to public function
    {
        $this->user->profile->update([
            'preferences->theme' => $theme,
        ]);

        $this->theme = $theme;
        $this->dispatch('theme-updated', theme: $theme);
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

        if ($this->email !== $this->user->email) {
            $this->requestEmailChange();
            // Important: Reset the local email property to the OLD one 
            // until the new one is actually verified.
            $this->email = $this->user->email; 
        }

        DB::transaction(function () {
            $this->user->update(['username' => $this->username]);

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
        $this->user->profile->update(['avatar' => $path]);
        $this->notification()->info('Avatar Changed', 'Your profile picture has been updated.');
    }

    public function requestEmailChange()
    {
        $code = (string) rand(100000, 999999);

        $this->user->update([
            'unverified_email' => $this->email,
            'verification_code' => $code,
            'verification_sent_at' => now(),
        ]);

        Mail::to($this->email)->send(new VerifyNewEmail($code));

        $this->user->refresh();
        $this->notification()->success('Verification Sent', 'Check your new email inbox.');
    }

    public function confirmEmailChange()
    {
        if (empty($this->enteredCode)) {
            $this->addError('enteredCode', 'Please enter the code.');
            return;
        }

        if ($this->user->verification_code === $this->enteredCode) {
            DB::transaction(function () {
                $this->user->forceFill([
                    'email' => $this->user->unverified_email,
                    'unverified_email' => null,
                    'verification_code' => null, // Clear the code
                    'email_verified_at' => now(),
                ])->save();
            });
            
            $this->user->refresh();
            $this->email = $this->user->email;
            $this->enteredCode = null;
            $this->notification()->success('Email Verified', 'Your account email has been updated.');
        } else {
            $this->notification()->error('Invalid Code', 'The code you entered is incorrect.');
        }
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'], // Validates against auth user
            'new_password'     => ['required', 'confirmed', Password::min(8)],
        ]);

        $this->user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        
        $this->notification()->success(
            title: 'Password Updated',
            description: 'Your security credentials have been changed successfully.'
        );
    }

    /**
     * Check if the general information has changed.
     */
    public function getIsDirtyProperty(): bool
    {
        return $this->first_name  !== ($this->user->profile->first_name ?? '') ||
               $this->middle_name !== ($this->user->profile->middle_name ?? '') ||
               $this->last_name   !== ($this->user->profile->last_name ?? '') ||
               $this->username    !== ($this->user->username ?? '') ||
               $this->email       !== ($this->user->email ?? '') ||
               $this->timezone    !== ($this->user->profile->timezone ?? 'UTC');
    }

    /**
     * Check if the password fields are ready to be submitted.
     */
    public function getCanUpdatePasswordProperty(): bool
    {
        return !empty($this->current_password) && 
               strlen($this->new_password) >= 8 && 
               $this->new_password === $this->new_password_confirmation;
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
        <div class="py-5 px-4">
            <div class="space-y-10">
                
                {{-- Header Section --}}
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="sm:text-3xl text-lg font-black text-base-content tracking-tight">Account Settings</h1>
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
                        <div class="bg-base-100 rounded-[2.5rem] p-8 text-center shadow-xl">
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
                            <h2 class="mt-4 font-bold font-roboto text-xl">{{ $user->fullname }}</h2>
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
                        <h1 class="sm:text-xl text-lg font-black text-base-800 tracking-tight sm:uppercase mb-6">Manage your Profile</h1>
                        <x-card title="Personal Information" rounded="3xl" shadow="none" class="border-base-200 bg-base-100 dark:bg-base-300">
                            <form wire:submit="updateGeneralInfo" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <x-input label="First Name" wire:model.live.blur="first_name" />
                                    <x-input label="Last Name" wire:model.live.blur="last_name" />
                                </div>
                                
                                <x-input label="Middle Name (Optional)" wire:model.live.blur="middle_name" />

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-base-200 pt-6 mt-6">
                                    <x-input label="Username" prefix="@" wire:model.live.blur="username" />
                                    <x-input label="Email Address" icon="envelope" wire:model.live.blur="email" />
                                </div>

                                <x-select
                                    label="Timezone"
                                    placeholder="Select Timezone"
                                    wire:model="timezone"
                                    :options="['UTC', 'PST', 'Asia/Manila', 'EST', 'GMT']"
                                />

                                {{-- <div class="flex justify-end pt-4">
                                    <x-button type="submit" primary label="Save All Changes" spinner="updateGeneralInfo" class="rounded-xl px-10" />
                                </div> --}}
                                <div class="flex justify-end pt-4">
                                    <x-button 
                                            type="submit" 
                                            primary 
                                            label="Save All Changes" 
                                            spinner="updateGeneralInfo" 
                                            class="rounded-xl px-10" 
                                            :disabled="!$this->isDirty" 
                                        />
                                </div>
                            </form>
                        </x-card>

                        {{-- Theme Preferences (using the JSON column) --}}
                        <div class="mt-10 border-t border-error/20 pt-10">
                            <h1 class="sm:text-xl font-black text-lg text-base-800 tracking-tight sm:uppercase mb-6">Manage your Theme</h1>
                            {{-- Status Indicator --}}
                            <x-card title="Preferences" rounded="3xl" shadow="none" class="border-base-200">
                                <div class="flex items-center space-x-2">
                                    <span class="text-[10px] font-bold uppercase tracking-tighter opacity-40 py-5">Current : </span>
                                    <div class="h-2 w-2 rounded-full bg-primary animate-pulse mr-2"></div>
                                    <span class="text-[10px] font-bold uppercase tracking-tighter opacity-40" x-text="theme"></span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <x-button outline interaction="positive" secondary label="Light Mode" icon="sun" wire:click="updateTheme('light')" />
                                    <x-button solid secondary label="Dark Mode" icon="moon" wire:click="updateTheme('dark')" />
                                    <x-button  @click="localStorage.removeItem('theme'); location.reload();">
                                        Use System Settings
                                    </x-button>
                                </div>
                            </x-card>
                        </div>
                        <div class="my-10 border-t border-error/20 pt-10">
                            <h1 class="sm:text-xl text-lg font-black text-base-800 tracking-tight sm:uppercase mb-6">Security Credentials</h1>
                            
                            <div class="grid grid-cols-1 gap-6">
                                {{-- Change Password Card --}}
                                <x-card title="Change Password" rounded="3xl" shadow="none" class="border-base-200">
                                    <form wire:submit="updatePassword" class="space-y-4">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <x-password 
                                                label="Current Password" 
                                                wire:model.live.blur="current_password" 
                                                placeholder="••••••••" 
                                            />
                                            <x-password 
                                                label="New Password" 
                                                wire:model.live.blur="new_password" 
                                                placeholder="••••••••" 
                                            />
                                            <x-password 
                                                label="Confirm New Password" 
                                                wire:model.live.blur="new_password_confirmation" 
                                                placeholder="••••••••" 
                                            />
                                        </div>
                                        
                                        <div class="flex justify-end mt-4">
                                            <x-button 
                                                type="submit" 
                                                negative 
                                                outline 
                                                label="Update Password" 
                                                spinner="updatePassword" 
                                                class="rounded-xl px-8"
                                                :disabled="!$this->canUpdatePassword"
                                            />
                                        </div>
    
                                    </form>
                                </x-card>

                                

                                {{-- Optional: Delete Account Card --}}
                                {{-- <div class="bg-error/5 border border-error/20 rounded-[2.5rem] p-8 flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-bold text-error">Delete Account</h3>
                                        <p class="text-sm text-base-content/60">Once your account is deleted, all data will be permanently removed.</p>
                                    </div>
                                    <x-button label="Delete Account" red flat class="font-bold" />
                                </div> --}}
                            </div>
                        </div>     
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-container>
