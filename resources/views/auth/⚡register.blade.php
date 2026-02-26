<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use WireUi\Traits\WireUiActions;

new #[Layout('layouts.auth')] #[Title('Create Account')] class extends Component
{
    use WireUiActions;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register()
    {
        $data = $this->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return $this->redirectIntended('/admin/dashboard', navigate: true);
    }
};
?>

<div class="min-h-screen flex sm:items-center item-start justify-center bg-base-200 px-4 py-12">
    <div class="card w-full max-w-md bg-base-100 shadow-2xl border border-base-300">
        <div class="card-body p-8">
            
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-secondary text-secondary-content mb-3 shadow-md">
                    <x-icon name="user-plus" class="w-7 h-7" />
                </div>
                <h1 class="text-2xl font-bold text-base-content">Create Account</h1>
                <p class="text-sm text-base-content/60">Join us today to get started</p>
            </div>

            <form wire:submit="register" class="space-y-4">
                <x-input 
                    label="Full Name" 
                    placeholder="John Doe" 
                    icon="user" 
                    wire:model="name"
                    mini
                />

                <x-input 
                    label="Email Address" 
                    placeholder="john@example.com" 
                    icon="envelope" 
                    wire:model="email"
                    mini 
                />

                <x-password 
                    label="Password" 
                    placeholder="Create a strong password" 
                    wire:model="password" 
                />

                <x-password 
                    label="Confirm Password" 
                    placeholder="Repeat your password" 
                    wire:model="password_confirmation" 
                />

                <div class="pt-4">
                    <x-button 
                        type="submit" 
                        secondary 
                        full 
                        lg 
                        label="Register Now" 
                        spinner="register" 
                    />
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-base-300 text-center">
                <p class="text-sm text-base-content/60">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-semibold text-secondary hover:underline" wire:navigate>Sign In</a>
                </p>
            </div>
        </div>
    </div>
</div>