<?php

use App\Models\User;
use App\Models\DigitalSignature;
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
    public string $theme = 'light';
    public $photo; 
    public ?string $enteredCode = null;
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';
    public string $secret_code_input = '';
    public string $confirm_secret_code = '';

    // Digital Signature Properties
    public string $signature_label = '';
    public string $signature_secret_code = '';
    public string $signature_confirm_code = '';

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

    public function updateTheme(string $theme)
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
                    'verification_code' => null,
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
            'current_password' => ['required', 'current_password'],
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

    public function generateEsignature()
    {
        $this->validate([
            'secret_code_input' => 'required|string|min:6',
            'confirm_secret_code' => 'required|same:secret_code_input',
        ]);

        $esignature = $this->user->generateEsignature($this->secret_code_input);

        $this->user->update([
            'secret_code' => $this->secret_code_input,
            'esignature' => $esignature,
        ]);

        $this->user->refresh();
        $this->secret_code_input = '';
        $this->confirm_secret_code = '';

        $this->notification()->success(
            title: 'Digital Signature Generated',
            description: 'Your esignature has been created successfully.'
        );
    }

    public function generateDigitalSignature()
    {
        $this->validate([
            'signature_label'       => 'nullable|string|max:255',
            'signature_secret_code' => 'required|string|min:6',
            'signature_confirm_code'=> 'required|same:signature_secret_code',
        ]);

        $hash = $this->user->generateEsignature($this->signature_secret_code);

        $this->user->digitalSignatures()->create([
            'label'            => $this->signature_label ?: 'Signature ' . ($this->user->digitalSignatures()->count() + 1),
            'esignature_hash'  => $hash,
            'is_active'        => !$this->user->digitalSignatures()->exists(),
        ]);

        $this->reset(['signature_label', 'signature_secret_code', 'signature_confirm_code']);

        $this->notification()->success(
            title: 'Signature Created',
            description: 'Your new digital signature has been generated.'
        );
    }

    public function activateSignature(string $id)
    {
        $signature = DigitalSignature::where('user_id', $this->user->id)->findOrFail($id);

        $this->user->digitalSignatures()->update(['is_active' => false]);
        $signature->update(['is_active' => true]);

        $this->notification()->success('Signature Activated', 'This signature is now your active signing key.');
    }

    public function deleteSignature(string $id)
    {
        $signature = DigitalSignature::where('user_id', $this->user->id)->findOrFail($id);

        if (!$this->user->isSuperAdmin()) {
            return $this->notification()->error('Unauthorized', 'Only super admins can delete signatures.');
        }

        if ($signature->isInUse()) {
            return $this->notification()->error('Cannot Delete', 'This signature is in use by an approved travel order.');
        }

        $this->dialog()->confirm([
            'title'       => 'Delete Signature?',
            'description' => 'This will permanently remove the signature "' . $signature->label . '".',
            'icon'        => 'trash',
            'accept'      => [
                'label'  => 'Delete',
                'color'  => 'negative',
                'method' => 'performDeleteSignature',
                'params' => $id,
            ],
            'reject' => [
                'label' => 'Cancel',
            ],
        ]);
    }

    public function performDeleteSignature(string $id)
    {
        $signature = DigitalSignature::where('user_id', $this->user->id)->findOrFail($id);

        if (!$this->user->isSuperAdmin()) {
            return $this->notification()->error('Unauthorized', 'Only super admins can delete signatures.');
        }

        if ($signature->isInUse()) {
            return $this->notification()->error('Cannot Delete', 'This signature is in use by an approved travel order.');
        }

        $signature->delete();

        $this->notification()->success('Deleted', 'Signature has been removed.');
    }

    public function getHasEsignatureProperty(): bool
    {
        return !empty($this->user->esignature);
    }

    public function getIsDirtyProperty(): bool
    {
        return $this->first_name  !== ($this->user->profile->first_name ?? '') ||
               $this->middle_name !== ($this->user->profile->middle_name ?? '') ||
               $this->last_name   !== ($this->user->profile->last_name ?? '') ||
               $this->username    !== ($this->user->username ?? '') ||
               $this->email       !== ($this->user->email ?? '') ||
               $this->timezone    !== ($this->user->profile->timezone ?? 'UTC');
    }

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
>
    {{-- Profile Header --}}
    <div class="flex flex-col sm:flex-row items-center gap-5 mb-8">
        <div class="relative group flex-shrink-0">
            <x-avatar size="w-24 h-24" rounded="3xl"
                src="{{ $user->profile->avatar ? asset('storage/'.$user->profile->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->initialed_name).'&background=random&bold=true&size=128' }}"
                class="ring-4 ring-base-200 shadow-lg"
            />
            <label class="absolute inset-0 flex items-center justify-center bg-black/40 rounded-3xl opacity-0 group-hover:opacity-100 cursor-pointer transition-all">
                <x-icon name="camera" class="w-6 h-6 text-white" />
                <input type="file" wire:model="photo" class="hidden" accept="image/*" />
            </label>
        </div>
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-black">{{ $user->full_name }}</h1>
                @if($user->email_verified_at)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 text-[10px] font-bold uppercase">
                        <x-icon name="check-badge" mini class="w-3 h-3" /> Verified
                    </span>
                @endif
                <x-badge :color="$user->status === 'active' ? 'positive' : 'warning'" label="{{ strtoupper($user->role) }}" flat class="font-bold" />
            </div>
            <p class="text-sm text-base-content/50 font-mono">{{ '@' . $user->username }}</p>
            @if($user->profile->position)
                <p class="text-sm text-base-content/60 mt-0.5">{{ $user->profile->position }} — {{ $user->profile->area_of_assignment }}</p>
            @endif
        </div>
    </div>

    {{-- Tabs --}}
    <div x-data="{ tab: 'overview' }">
        <div class="border-b border-base-200 flex gap-0 overflow-x-auto">
            <button @click="tab = 'overview'" :class="tab === 'overview' ? 'border-primary text-primary' : 'border-transparent text-base-content/50 hover:text-base-content/80'" class="flex items-center gap-2 px-5 py-3 text-sm font-bold border-b-2 transition-colors whitespace-nowrap">
                <x-icon name="user" class="w-4 h-4" /> Overview
            </button>
            <button @click="tab = 'security'" :class="tab === 'security' ? 'border-primary text-primary' : 'border-transparent text-base-content/50 hover:text-base-content/80'" class="flex items-center gap-2 px-5 py-3 text-sm font-bold border-b-2 transition-colors whitespace-nowrap">
                <x-icon name="shield-check" class="w-4 h-4" /> Security
            </button>
            <button @click="tab = 'signatures'" :class="tab === 'signatures' ? 'border-primary text-primary' : 'border-transparent text-base-content/50 hover:text-base-content/80'" class="flex items-center gap-2 px-5 py-3 text-sm font-bold border-b-2 transition-colors whitespace-nowrap">
                <x-icon name="key" class="w-4 h-4" /> Signatures
            </button>
            <button @click="tab = 'appearance'" :class="tab === 'appearance' ? 'border-primary text-primary' : 'border-transparent text-base-content/50 hover:text-base-content/80'" class="flex items-center gap-2 px-5 py-3 text-sm font-bold border-b-2 transition-colors whitespace-nowrap">
                <x-icon name="paint-brush" class="w-4 h-4" /> Appearance
            </button>
        </div>

        <div class="py-8">

            {{-- OVERVIEW --}}
            <div x-show="tab === 'overview'" x-transition>
                <x-card rounded="3xl" shadow="none" class="border-base-200">
                    <form wire:submit="updateGeneralInfo" class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-input label="First Name" wire:model.live.blur="first_name" icon="user" />
                            <x-input label="Last Name" wire:model.live.blur="last_name" />
                        </div>
                        <x-input label="Middle Name" wire:model.live.blur="middle_name" placeholder="Optional" />
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-input label="Username" prefix="@" wire:model.live.blur="username" />
                            <x-input label="Email" icon="envelope" wire:model.live.blur="email" />
                        </div>
                        <x-select label="Timezone" wire:model="timezone" :options="['UTC', 'PST', 'Asia/Manila', 'EST', 'GMT']" />

                        @if($user->unverified_email)
                            <div class="bg-amber-500/5 border border-amber-500/20 rounded-2xl p-4">
                                <div class="flex items-start gap-3">
                                    <x-icon name="information-circle" class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" />
                                    <div class="flex-1">
                                        <p class="text-xs font-bold text-amber-600 uppercase tracking-wider">Verify New Email</p>
                                        <p class="text-sm text-base-content/60 mt-1">A code was sent to <span class="font-semibold">{{ $user->unverified_email }}</span></p>
                                        <div class="mt-3 flex items-end gap-2">
                                            <x-input placeholder="000000" wire:model="enteredCode" x-mask="999999" class="text-center tracking-[0.5em] font-mono text-lg max-w-[160px]" />
                                            <x-button primary label="Verify" wire:click="confirmEmailChange" spinner="confirmEmailChange" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="flex justify-end pt-2">
                            <x-button type="submit" primary label="Save Changes" spinner="updateGeneralInfo" class="rounded-xl px-8" :disabled="!$this->isDirty" />
                        </div>
                    </form>
                </x-card>
            </div>

            {{-- SECURITY --}}
            <div x-show="tab === 'security'" x-transition x-cloak>
                <x-card rounded="3xl" shadow="none" class="border-base-200">
                    <form wire:submit="updatePassword" class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <x-password label="Current Password" wire:model.live.blur="current_password" placeholder="Enter current" />
                            <x-password label="New Password" wire:model.live.blur="new_password" placeholder="Min. 8 characters" />
                            <x-password label="Confirm New" wire:model.live.blur="new_password_confirmation" placeholder="Repeat new" />
                        </div>
                        <div class="flex justify-end">
                            <x-button type="submit" negative outline label="Update Password" spinner="updatePassword" class="rounded-xl px-8" :disabled="!$this->canUpdatePassword" />
                        </div>
                    </form>
                </x-card>
            </div>

            {{-- SIGNATURES --}}
            <div x-show="tab === 'signatures'" x-transition x-cloak>
                <div class="space-y-6">
                    @php $activeSig = $this->user->digitalSignatures()->where('is_active', true)->first(); @endphp
                    <x-card rounded="3xl" shadow="none" class="border-base-200">
                        @if($activeSig)
                            <div class="bg-success/5 border border-success/20 rounded-2xl p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-success/10">
                                        <x-icon name="check-badge" class="w-5 h-5 text-success" />
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-success">Active Signature</h3>
                                        <p class="text-xs opacity-60">{{ $activeSig->label }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-2">Hash</p>
                                        <div class="bg-base-100 rounded-xl p-4 font-mono text-xs break-all leading-relaxed">{{ $activeSig->formatted_hash }}</div>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mb-2">QR Code</p>
                                        <div class="bg-base-100 rounded-xl p-4 flex items-center justify-center">
                                            @if($qrCode = $activeSig->generateQrCodePng())
                                                <img src="{{ $qrCode }}" alt="QR" class="h-24" />
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-10">
                                <div class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-base-200 mb-3">
                                    <x-icon name="key" class="w-7 h-7 opacity-40" />
                                </div>
                                <p class="text-sm opacity-50">No active signature. Create one below.</p>
                            </div>
                        @endif
                    </x-card>

                    <x-card rounded="3xl" shadow="none" class="border-base-200">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-base-content/40 mb-4">Create New Signature</h3>
                        <form wire:submit="generateDigitalSignature" class="space-y-4">
                            <x-input label="Label" wire:model="signature_label" placeholder="e.g. Official Signature, PARPO II" />
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <x-password label="Secret Code" wire:model.live.blur="signature_secret_code" placeholder="Min. 6 characters" />
                                <x-password label="Confirm Code" wire:model.live.blur="signature_confirm_code" placeholder="Re-enter" />
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-warning/5 border border-warning/10 rounded-xl">
                                <x-icon name="exclamation-triangle" class="w-4 h-4 text-warning flex-shrink-0" />
                                <p class="text-xs text-warning/80">Keep your secret code confidential.</p>
                            </div>
                            <div class="flex justify-end">
                                <x-button type="submit" primary outline label="Generate" icon="key" spinner="generateDigitalSignature" class="rounded-xl px-6" />
                            </div>
                        </form>
                    </x-card>

                    @php $signatures = $this->user->digitalSignatures()->latest()->get(); @endphp
                    @if($signatures->count())
                    <x-card rounded="3xl" shadow="none" class="border-base-200">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-base-content/40 mb-4">All Signatures</h3>
                        <div class="divide-y divide-base-200">
                            @foreach($signatures as $sig)
                                @php $inUse = $sig->isInUse(); @endphp
                                <div class="flex items-center gap-4 py-4 first:pt-0 last:pb-0">
                                    <input type="radio" name="active_signature" {{ $sig->is_active ? 'checked' : '' }}
                                        wire:change="activateSignature('{{ $sig->id }}')"
                                        class="radio radio-success radio-sm" />
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-bold text-sm">{{ $sig->label }}</span>
                                            @if($sig->is_active)
                                                <span class="badge badge-success badge-xs">Active</span>
                                            @endif
                                            @if($inUse)
                                                <span class="badge badge-info badge-xs">In Use</span>
                                            @endif
                                        </div>
                                        <p class="font-mono text-xs opacity-50 truncate">{{ $sig->formatted_hash }}</p>
                                        <p class="text-[11px] opacity-30 mt-0.5">Created {{ $sig->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if($inUse)
                                        <span class="opacity-30"><x-icon name="lock-closed" class="w-4 h-4" /></span>
                                    @elseif($this->user->isSuperAdmin())
                                        <x-button flat negative icon="trash" wire:click="deleteSignature('{{ $sig->id }}')" />
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </x-card>
                    @endif
                </div>
            </div>

            {{-- APPEARANCE --}}
            <div x-show="tab === 'appearance'" x-transition x-cloak>
                <x-card rounded="3xl" shadow="none" class="border-base-200">
                    <p class="text-sm font-bold mb-4">Theme Preference</p>
                    <div class="flex items-center gap-3">
                        <button wire:click="updateTheme('light')" 
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 transition-all {{ $theme === 'light' ? 'border-primary bg-primary/5 text-primary' : 'border-base-200 text-base-content/50 hover:border-base-300' }}">
                            <x-icon name="sun" class="w-4 h-4" /> Light
                        </button>
                        <button wire:click="updateTheme('dark')" 
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 transition-all {{ $theme === 'dark' ? 'border-primary bg-primary/5 text-primary' : 'border-base-200 text-base-content/50 hover:border-base-300' }}">
                            <x-icon name="moon" class="w-4 h-4" /> Dark
                        </button>
                        <button @click="localStorage.removeItem('theme'); location.reload();" 
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-base-200 text-base-content/50 hover:border-base-300 transition-all">
                            <x-icon name="computer-desktop" class="w-4 h-4" /> System
                        </button>
                    </div>
                </x-card>
            </div>

        </div>
    </div>
</x-main-container>
