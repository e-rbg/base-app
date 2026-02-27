<?php

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;
use WireUi\Traits\WireUiActions;

new #[Layout('layouts.auth', ['title' => 'Register'])] class extends Component {
    use WireUiActions;

    // User Table Data
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    // Profile Table Data
    public string $first_name = '';
    public string $middle_name = '';
    public string $last_name = '';

    public function register()
    {
        $this->validate([
            'username'   => ['required', 'string', 'alpha_dash', 'max:20', 'unique:users'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
            'first_name' => ['required', 'string', 'max:50'],
            'last_name'  => ['required', 'string', 'max:50'],
        ]);

        try {
            DB::transaction(function () {
                // 1. Create the User (UUID is handled by the HasUuids trait)
                $user = User::create([
                    'username' => $this->username,
                    'email'    => $this->email,
                    'password' => Hash::make($this->password),
                    'status'   => 'active',
                ]);

                // 2. Create the associated Profile
                $user->profile()->create([
                    'first_name'  => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name'   => $this->last_name,
                    'timezone'    => 'UTC', // You can detect this via JS later
                ]);

                Auth::login($user);
            });

            return $this->redirectIntended('/admin/dashboard', navigate: true);

        } catch (\Exception $e) {
            $this->notification()->error(
                title: 'Registration Error',
                description: 'Something went wrong while creating your account.'
            );
        }
    }
}; ?>

<div class="min-h-screen sm:flex sm:items-center items-start sm:pt-0 sm:justify-center bg-base-200 px-4">
    <div class="card w-full max-w-xl bg-base-100 shadow-2xl border border-base-300">
        <div class="card-body p-8">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-primary text-primary-content mb-3 shadow-md">
                    <x-icon name="lock-closed" class="w-7 h-7" />
                </div>
                <h1 class="text-3xl font-black tracking-tight text-base-content">Create Account</h1>
                <p class="text-sm opacity-60">Register your credentials here</p>
            </div>

            <form wire:submit="register" class="space-y-4">
                {{-- Row 1: Names --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-input label="First Name" wire:model="first_name" placeholder="John" />
                    <x-input label="Middle Name" wire:model="middle_name" placeholder="D." />
                    <x-input label="Last Name" wire:model="last_name" placeholder="Doe" />
                </div>

                {{-- Row 2: Account Details --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input label="Username" icon="at-symbol" wire:model="username" placeholder="johndoe" />
                    <x-input label="Email" icon="envelope" wire:model="email" placeholder="john@example.com" />
                </div>

                <hr class="border-base-300 my-2" />

                {{-- Row 3: Passwords --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-password label="Password" wire:model="password" />
                    <x-password label="Confirm Password" wire:model="password_confirmation" />
                </div>

                <div class="pt-4">
                    <x-button type="submit" primary full lg label="Sign Up" spinner="register" />
                </div>
            </form>

            <p class="mt-6 text-center text-sm opacity-60">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-bold text-primary hover:underline" wire:navigate>Login here</a>
            </p>
        </div>
    </div>
</div>