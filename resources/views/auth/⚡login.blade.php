<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use WireUi\Traits\WireUiActions; // For toast notifications

new #[Layout('layouts.auth')] #[Title('Login')] class extends Component
{
    use WireUiActions;

    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login()
    {
        $this->validate([
            'email' => ['required'], // renamed to 'login' conceptually
            'password' => ['required'],
        ]);

        // Check if input is email or username
        $fieldType = filter_var($this->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$fieldType => $this->email, 'password' => $this->password, 'status' => 'active'], $this->remember)) {
            $user = Auth::user();

            // Audit: Capture the login time and IP address
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => request()->ip(),
            ]);

            session()->regenerate();
            return $this->redirectIntended('/admin/dashboard', navigate: true);
        }

        // Handle case where user exists but is not 'active'
        $userExists = \App\Models\User::where('email', $this->email)->first();
        if ($userExists && $userExists->status !== 'active') {
            $this->notification()->warning(
                title: 'Account Restricted',
                description: "Your account is currently {$userExists->status}. Please contact support."
            );
            return;
        }

        $this->notification()->error(
            title: 'Authentication Failed',
            description: 'The provided credentials do not match our records.'
        );

        $this->reset('password');
    }
};
?>

<div class="min-h-screen flex sm:items-center items-start sm:pt-0 pt-10 justify-center bg-base-200 px-4">
    <div class="card w-full max-w-md bg-base-100 shadow-2xl border border-base-300">
        <div class="card-body p-8">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-primary text-primary-content mb-3 shadow-md">
                    <x-icon name="lock-closed" class="w-7 h-7" />
                </div>
                <h1 class="text-2xl font-bold text-base-content">Welcome Back</h1>
                <p class="text-sm text-base-content/60">Enter your account details to continue</p>
            </div>

            <form wire:submit="login" class="space-y-6">
                <x-input 
                    label="Email Address" 
                    placeholder="name@example.com" 
                    icon="envelope" 
                    wire:model="email" 
                />

                <x-password 
                    label="Password" 
                    placeholder="••••••••" 
                    wire:model="password" 
                />

                <div class="flex items-center justify-between">
                    <x-checkbox label="Remember me" wire:model="remember" />
                    <a href="#" class="text-sm font-medium text-primary hover:text-primary-focus transition-colors">
                        Forgot password?
                    </a>
                </div>

                <div class="pt-2">
                    <x-button 
                        type="submit" 
                        primary 
                        full 
                        lg 
                        label="Login to Dashboard" 
                        spinner="login" 
                    />
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-base-300 text-center">
                <p class="text-sm text-base-content/60">
                    New here? 
                    <a href="{{ route('register') }}" class="font-semibold text-primary hover:underline" wire:navigate>Create an account</a>
                </p>
            </div>
        </div>
    </div>
</div>