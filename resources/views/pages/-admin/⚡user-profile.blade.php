<?php

use App\Models\User;
use App\Models\DigitalSignature;
use App\Models\TravelOrder;
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
    public string $extension = '';
    public $academic_titles = '';
    public string $gender = '';
    public string $marital_status = '';
    public string $spouse = '';
    public string $blood_type = '';
    public string $address = '';
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

    // Employment Details Properties
    public ?string $emp_employee_id = null;
    public string $emp_position = '';
    public string $emp_assignment = '';
    public string $emp_designation = '';
    public string $emp_employment_status = '';
    public string $emp_salary_grade = '';
    public string $emp_step_increment = '';
    public ?string $emp_monthly_salary = null;
    public ?string $emp_employed_in_dar_since = null;
    public ?string $emp_employed_in_government_since = null;

    // Digital Signature Properties
    public string $signature_label = '';
    public string $signature_secret_code = '';
    public string $signature_confirm_code = '';
    public string $verifyInput = '';
    public ?bool $verificationResult = null;
    public ?string $verifiedSignatory = null;
    public ?string $verifiedRole = null;
    public ?string $verifiedOrderId = null;

    public function mount()
    {
        $this->user = auth()->user()->load('profile.employeeInformation');
        $emp = $this->user->profile->employeeInformation;

        $this->first_name     = $this->user->profile->first_name;
        $this->middle_name    = $this->user->profile->middle_name ?? '';
        $this->last_name      = $this->user->profile->last_name;
        $this->extension      = $this->user->profile->extension ?? '';
        $this->academic_titles = $this->user->profile->academic_titles ?? '';
        $this->gender         = $this->user->profile->gender ?? '';
        $this->marital_status = $this->user->profile->marital_status ?? '';
        $this->spouse         = $this->user->profile->spouse ?? '';
        $this->blood_type     = $this->user->profile->blood_type ?? '';
        $this->address        = $this->user->profile->address ?? '';
        $this->timezone                      = $this->user->profile->timezone;
        $this->username                       = $this->user->username ?? '';
        $this->email                          = $this->user->email;
        $this->theme                          = $this->user->profile->preferences['theme'] ?? 'light';
        $this->emp_employee_id                = $emp?->employee_id ?? '';
        $this->emp_position                   = $emp?->position ?? '';
        $this->emp_assignment                 = $emp?->assignment ?? '';
        $this->emp_designation                = $emp?->designation ?? '';
        $this->emp_employment_status          = $emp?->employment_status ?? '';
        $this->emp_salary_grade               = $emp?->salary_grade ?? '';
        $this->emp_step_increment             = $emp?->step_increment ?? '';
        $this->emp_monthly_salary             = $emp?->monthly_salary ?? '';
        $this->emp_employed_in_dar_since       = $emp?->employed_in_dar_since?->format('Y-m-d') ?? '';
        $this->emp_employed_in_government_since = $emp?->employed_in_government_since?->format('Y-m-d') ?? '';
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
                'first_name'     => $this->first_name,
                'middle_name'    => $this->middle_name,
                'last_name'      => $this->last_name,
                'extension'      => $this->extension ?: null,
                'academic_titles' => $this->academic_titles ?: null,
                'gender'         => $this->gender ?: null,
                'marital_status' => $this->marital_status ?: null,
                'spouse'         => $this->spouse ?: null,
                'blood_type'     => $this->blood_type ?: null,
                'address'        => $this->address ?: null,
                'timezone'       => $this->timezone,
            ]);
        });

        $this->notification()->success('Profile Updated', 'Changes saved.');
    }

    public function updateEmploymentDetails()
    {
        $this->user->profile->load('employeeInformation');

        $data = [
            'employee_id'                => $this->emp_employee_id ?: null,
            'position'                   => $this->emp_position ?: null,
            'assignment'                 => $this->emp_assignment ?: null,
            'designation'                => $this->emp_designation ?: null,
            'employment_status'          => $this->emp_employment_status ?: null,
            'salary_grade'               => $this->emp_salary_grade ?: null,
            'step_increment'             => $this->emp_step_increment ?: null,
            'monthly_salary'             => $this->emp_monthly_salary ?: null,
            'employed_in_dar_since'       => $this->emp_employed_in_dar_since ?: null,
            'employed_in_government_since' => $this->emp_employed_in_government_since ?: null,
        ];

        $this->user->profile->employeeInformation()->updateOrCreate(
            ['user_profile_id' => $this->user->profile->id],
            $data
        );

        $this->notification()->success('Employment Details Saved', 'Your employment information has been updated.');
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

    public function verifySignature(): void
    {
        $this->validate([
            'verifyInput' => 'required|string',
        ]);

        $input = trim($this->verifyInput);

        // Check if input is a scanned verification URL (contains /verify/ORDER-ID)
        if (preg_match('#/verify/([a-f0-9-]{36})#i', $input, $m)) {
            $order = TravelOrder::find($m[1]);
            if ($order) {
                $this->verificationResult = true;
                $this->verifiedSignatory = $order->approved_by_name ?? $order->recommending_approval;
                $this->verifiedRole = $order->approved_by_position ?? $order->recommending_position;
                $this->verifiedOrderId = $order->id;
            } else {
                $this->verificationResult = false;
                $this->verifiedSignatory = null;
                $this->verifiedRole = null;
                $this->verifiedOrderId = null;
            }
            return;
        }

        $order = TravelOrder::where('esignature_hash', $input)
            ->orWhere('esignature_recommender_hash', $input)
            ->first();

        if ($order) {
            $this->verificationResult = true;
            $this->verifiedOrderId = $order->id;

            if ($order->esignature_hash === $input) {
                $this->verifiedSignatory = $order->approved_by_name;
                $this->verifiedRole = $order->approved_by_position;
            } else {
                $this->verifiedSignatory = $order->recommending_approval;
                $this->verifiedRole = $order->recommending_position;
            }
        } else {
            $this->verificationResult = false;
            $this->verifiedSignatory = null;
            $this->verifiedRole = null;
            $this->verifiedOrderId = null;
        }
    }

    public function getHasEsignatureProperty(): bool
    {
        return !empty($this->user->esignature);
    }

    public function getIsDirtyProperty(): bool
    {
        return $this->first_name     !== ($this->user->profile->first_name ?? '') ||
               $this->middle_name    !== ($this->user->profile->middle_name ?? '') ||
               $this->last_name      !== ($this->user->profile->last_name ?? '') ||
               $this->extension      !== ($this->user->profile->extension ?? '') ||
               $this->academic_titles !== ($this->user->profile->academic_titles ?? '') ||
               $this->gender         !== ($this->user->profile->gender ?? '') ||
               $this->marital_status !== ($this->user->profile->marital_status ?? '') ||
               $this->spouse         !== ($this->user->profile->spouse ?? '') ||
               $this->blood_type     !== ($this->user->profile->blood_type ?? '') ||
               $this->address        !== ($this->user->profile->address ?? '') ||
               $this->username       !== ($this->user->username ?? '') ||
               $this->email          !== ($this->user->email ?? '') ||
               $this->timezone       !== ($this->user->profile->timezone ?? 'UTC');
    }

    public function getIsEmploymentDirtyProperty(): bool
    {
        $emp = $this->user->profile->employeeInformation;
        return $this->emp_employee_id                 !== ($emp?->employee_id ?? '') ||
               $this->emp_position                    !== ($emp?->position ?? '') ||
               $this->emp_assignment                  !== ($emp?->assignment ?? '') ||
               $this->emp_designation                 !== ($emp?->designation ?? '') ||
                $this->emp_employment_status           !== ($emp?->employment_status ?? '') ||
                $this->emp_salary_grade                !== ($emp?->salary_grade ?? '') ||
                $this->emp_step_increment              !== ($emp?->step_increment ?? '') ||
                $this->emp_monthly_salary              !== ($emp?->monthly_salary ?? '') ||
                $this->emp_employed_in_dar_since        !== ($emp?->employed_in_dar_since?->format('Y-m-d') ?? '') ||
                $this->emp_employed_in_government_since !== ($emp?->employed_in_government_since?->format('Y-m-d') ?? '');
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
            <button @click="tab = 'authenticator'" :class="tab === 'authenticator' ? 'border-primary text-primary' : 'border-transparent text-base-content/50 hover:text-base-content/80'" class="flex items-center gap-2 px-5 py-3 text-sm font-bold border-b-2 transition-colors whitespace-nowrap">
                <x-icon name="qr-code" class="w-4 h-4" /> TO Authenticator
            </button>
            <button @click="tab = 'employment'" :class="tab === 'employment' ? 'border-primary text-primary' : 'border-transparent text-base-content/50 hover:text-base-content/80'" class="flex items-center gap-2 px-5 py-3 text-sm font-bold border-b-2 transition-colors whitespace-nowrap">
                <x-icon name="briefcase" class="w-4 h-4" /> Employment Details
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
                        <x-input label="Extension" wire:model.live.blur="extension" placeholder="e.g. Jr., III (Optional)" />
                        <x-input label="Academic Titles" wire:model.live.blur="academic_titles" placeholder="e.g. PhD, MSc (Optional)" />
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <x-select label="Gender" wire:model="gender" placeholder="Select gender" :options="['Male', 'Female', 'Prefer not to say']" />
                            <x-select label="Marital Status" wire:model="marital_status" placeholder="Select status" :options="['Single', 'Married', 'Divorced', 'Widowed']" />
                            <x-input label="Blood Type" wire:model.live.blur="blood_type" placeholder="e.g. O+ (Optional)" />
                        </div>
                        <x-input label="Spouse" wire:model.live.blur="spouse" placeholder="Spouse name (Optional)" />
                        <x-textarea label="Address" wire:model.live.blur="address" placeholder="Complete address (Optional)" rows="2" />
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

                    {{-- Verify Signature --}}
                    <x-card rounded="3xl" shadow="none" class="border-base-200">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-base-content/40 mb-4">Verify Signature</h3>
                        <p class="text-xs text-base-content/50 mb-4">Paste a signature hash from a printed travel order to verify whose signature it belongs to.</p>
                        <div class="flex items-end gap-3">
                            <div class="flex-1">
                                <x-input wire:model.live.blur="verifyInput" placeholder="Paste the signature hash here"
                                    icon="finger-print" shadow="none" />
                            </div>
                            <x-button primary label="Verify" wire:click="verifySignature" spinner="verifySignature" />
                        </div>
                        @if($verificationResult !== null)
                            <div class="mt-4">
                                @if($verificationResult && $verifiedOrderId)
                                    @php $order = App\Models\TravelOrder::find($verifiedOrderId); @endphp
                                    <div class="flex items-start gap-3 text-sm text-green-600 bg-green-50 dark:bg-green-500/5 border border-green-200 dark:border-green-500/20 rounded-xl p-4">
                                        <x-icon name="check-circle" class="w-5 h-5 flex-shrink-0 mt-0.5" />
                                        <div>
                                            <p class="font-semibold">Signature is valid</p>
                                            <p class="text-xs opacity-70">
                                                Belongs to <span class="font-semibold">{{ $verifiedSignatory }}</span>
                                                @if($verifiedRole) — {{ $verifiedRole }} @endif
                                            </p>
                                            @if($order)
                                                <p class="text-xs opacity-50 mt-1">
                                                    Used on Travel Order <span class="font-mono font-semibold">{{ $order->travel_order_no }}</span>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-start gap-3 text-sm text-red-600 bg-red-50 dark:bg-red-500/5 border border-red-200 dark:border-red-500/20 rounded-xl p-4">
                                        <x-icon name="x-circle" class="w-5 h-5 flex-shrink-0 mt-0.5" />
                                        <div>
                                            <p class="font-semibold">No match found</p>
                                            <p class="text-xs opacity-70 mt-0.5">This signature does not exist on any approved travel order.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </x-card>
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

            {{-- TO AUTHENTICATOR --}}
            <div x-show="tab === 'authenticator'" x-transition x-cloak>
                <div class="space-y-6">
                    <x-card rounded="3xl" shadow="none" class="border-base-200">
                        <div x-data="{ showScanner: false }">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-base-content/40 mb-4">Travel Order Authenticator</h3>
                            <p class="text-xs text-base-content/50 mb-4">Scan the QR code on a printed travel order or paste a signature hash to verify its authenticity.</p>
                            <div class="flex items-end gap-3">
                                <div class="flex-1">
                                    <x-input wire:model.live.blur="verifyInput" placeholder="Paste signature hash or scan QR code"
                                        icon="finger-print" shadow="none" />
                                </div>
                                <x-button primary label="Verify" wire:click="verifySignature" spinner="verifySignature" />
                                <x-button secondary outline icon="camera" label="Scan QR"
                                    x-on:click="showScanner = !showScanner"
                                    x-text="showScanner ? 'Close Camera' : 'Scan QR'" />
                            </div>

                            {{-- QR Scanner --}}
                            <div x-cloak x-show="showScanner" x-transition
                                x-data="{
                                    scanner: null,
                                    init() {
                                        this.$watch('showScanner', async (value) => {
                                            if (value) {
                                                await this.$nextTick();
                                                const { Html5Qrcode } = window;
                                                this.scanner = new Html5Qrcode('qr-reader');
                                                this.scanner.start(
                                                    { facingMode: 'environment' },
                                                    { fps: 10, qrbox: { width: 250, height: 250 } },
                                                    decodedText => {
                                                        $wire.set('verifyInput', decodedText);
                                                        $wire.verifySignature();
                                                        if (this.scanner) {
                                                            this.scanner.stop();
                                                            this.scanner = null;
                                                        }
                                                        showScanner = false;
                                                    },
                                                    () => {}
                                                );
                                            } else if (this.scanner) {
                                                this.scanner.stop();
                                                this.scanner = null;
                                            }
                                        });
                                    },
                                    destroy() {
                                        if (this.scanner) {
                                            this.scanner.stop();
                                            this.scanner = null;
                                        }
                                    }
                                }">
                                <div id="qr-reader" class="w-full max-w-sm mx-auto mt-4"></div>
                                <p class="text-xs text-center text-base-content/40 mt-2">Point your camera at the document QR code</p>
                            </div>
                        </div>

                        @if($verificationResult !== null && $verifiedOrderId)
                            @php $order = App\Models\TravelOrder::find($verifiedOrderId); @endphp
                            @if($order)
                                <div class="mt-6 pt-6 border-t border-base-200">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-sm font-bold uppercase tracking-wider text-base-content/40">Travel Order Details</h4>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                            {{ $order->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($order->status === 'disapproved' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                            <span class="w-2 h-2 rounded-full {{ $order->status === 'approved' ? 'bg-emerald-500' : ($order->status === 'disapproved' ? 'bg-red-500' : 'bg-amber-500') }}"></span>
                                            {{ $order->status }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-base-content/40">Order No.</p>
                                            <p class="font-semibold font-mono">{{ $order->travel_order_no }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-base-content/40">Travel Date</p>
                                            <p class="font-semibold">{{ $order->travel_date?->format('F d, Y') ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-base-content/40">Personnel Name</p>
                                            <p class="font-semibold">{{ $order->name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-base-content/40">Position</p>
                                            <p class="font-semibold">{{ $order->position }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-base-content/40">Station</p>
                                            <p class="font-semibold">{{ $order->station }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-base-content/40">Destination</p>
                                            <p class="font-semibold">{{ $order->formattedDestination() }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-base-content/40">Departure</p>
                                            <p class="font-semibold">{{ $order->departure_date?->format('M d, Y') ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-base-content/40">Return</p>
                                            <p class="font-semibold">{{ $order->return_date?->format('M d, Y') ?? '—' }}</p>
                                        </div>
                                    </div>

                                    {{-- Signatories --}}
                                    <div class="mt-5 pt-4 border-t border-base-200">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-base-content/40 mb-3">Signatories</p>
                                        <div class="space-y-3">
                                            @if($order->approved_by_name)
                                                <div class="flex items-start gap-3 p-3 rounded-xl {{ $order->status === 'approved' ? 'bg-emerald-50 border border-emerald-200' : 'bg-base-100 border border-base-200' }}">
                                                    <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $order->status === 'approved' ? 'bg-emerald-200 text-emerald-700' : 'bg-base-200 text-base-content/50' }} flex-shrink-0">
                                                        <x-icon name="check" class="w-4 h-4" />
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-bold">{{ $order->approved_by_name }}</p>
                                                        <p class="text-xs text-base-content/50">{{ $order->approved_by_position ?? 'Approving Authority' }}</p>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($order->recommending_approval)
                                                <div class="flex items-start gap-3 p-3 rounded-xl bg-base-100 border border-base-200">
                                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-base-200 text-base-content/50 flex-shrink-0">
                                                        <x-icon name="user" class="w-4 h-4" />
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-bold">{{ $order->recommending_approval }}</p>
                                                        <p class="text-xs text-base-content/50">{{ $order->recommending_position ?? 'Recommending Authority' }}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Verification Result Badge --}}
                                    <div class="mt-5 pt-4 border-t border-base-200">
                                        @if($verificationResult)
                                            <div class="flex items-center gap-2 p-3 rounded-xl bg-emerald-500/5 border border-emerald-500/20 text-emerald-700">
                                                <x-icon name="shield-check" class="w-5 h-5 flex-shrink-0" />
                                                <div>
                                                    <p class="text-sm font-bold">Document is authentic</p>
                                                    <p class="text-xs opacity-70">Signed by <span class="font-semibold">{{ $verifiedSignatory }}</span> — {{ $verifiedRole }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2 p-3 rounded-xl bg-red-500/5 border border-red-500/20 text-red-700">
                                                <x-icon name="x-circle" class="w-5 h-5 flex-shrink-0" />
                                                <div>
                                                    <p class="text-sm font-bold">No match found</p>
                                                    <p class="text-xs opacity-70">This signature does not exist on any approved travel order.</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @elseif($verificationResult !== null && !$verifiedOrderId)
                            <div class="mt-4">
                                <div class="flex items-start gap-3 text-sm text-red-600 bg-red-50 dark:bg-red-500/5 border border-red-200 dark:border-red-500/20 rounded-xl p-4">
                                    <x-icon name="x-circle" class="w-5 h-5 flex-shrink-0 mt-0.5" />
                                    <div>
                                        <p class="font-semibold">No match found</p>
                                        <p class="text-xs opacity-70 mt-0.5">This signature does not exist on any approved travel order.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </x-card>
                </div>
            </div>

            {{-- EMPLOYMENT DETAILS --}}
            <div x-show="tab === 'employment'" x-transition x-cloak>
                <x-card rounded="3xl" shadow="none" class="border-base-200">
                    <form wire:submit="updateEmploymentDetails" class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-input label="Employee ID" wire:model.live.blur="emp_employee_id" placeholder="Agency-assigned ID" />
                            <x-input label="Position" wire:model.live.blur="emp_position" placeholder="Official position title" />
                        </div>
                        <x-select label="Assignment" wire:model="emp_assignment" placeholder="Select assignment"
                            :options="[
                                'OPARO' => 'OPARO — Nabunturan',
                                'LTID' => 'LTID — Nabunturan',
                                'PBDD' => 'PBDD — Nabunturan',
                                'DARMO-Compostela' => 'DARMO — Compostela',
                                'DARMO-Laak' => 'DARMO — Laak',
                                'DARMO-Mabini' => 'DARMO — Mabini',
                                'DARMO-Maco' => 'DARMO — Maco',
                                'DARMO-Maragusan' => 'DARMO — Maragusan',
                                'DARMO-Mawab' => 'DARMO — Mawab',
                                'DARMO-Monkayo' => 'DARMO — Monkayo',
                                'DARMO-Montevista' => 'DARMO — Montevista',
                                'DARMO-Nabunturan' => 'DARMO — Nabunturan',
                                'DARMO-New Bataan' => 'DARMO — New Bataan',
                                'DARMO-Pantukan' => 'DARMO — Pantukan',
                            ]" />
                        <x-input label="Designation" wire:model.live.blur="emp_designation" placeholder="e.g. OIC MARPO / SARPO" />
                        <x-select label="Employment Status" wire:model="emp_employment_status" placeholder="Select status"
                            :options="['Permanent', 'Contract of Service', 'Job Order', 'Casual', 'Coterminous', 'Elected']" />
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <x-input label="Salary Grade" wire:model.live.blur="emp_salary_grade" placeholder="e.g. 18" />
                            <x-input label="Step Increment" wire:model.live.blur="emp_step_increment" placeholder="e.g. 1" />
                            <x-input label="Monthly Salary (₱)" wire:model.live.blur="emp_monthly_salary" prefix="₱" money=".00" placeholder="0.00" />
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-input label="Employed in DAR Since" wire:model.live.blur="emp_employed_in_dar_since" type="date" />
                            <x-input label="Employed in Government Since" wire:model.live.blur="emp_employed_in_government_since" type="date" />
                        </div>
                        <div class="flex justify-end pt-2">
                            <x-button type="submit" primary label="Save Employment Details" spinner="updateEmploymentDetails" class="rounded-xl px-8" :disabled="!$this->isEmploymentDirty" />
                        </div>
                    </form>
                </x-card>
            </div>

        </div>
    </div>
</x-main-container>
