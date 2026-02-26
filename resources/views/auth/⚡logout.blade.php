<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use WireUi\Traits\WireUiActions;

new class extends Component
{
    use WireUiActions;

    public function confirmLogout(): void
    {
        $this->dialog()->confirm([
            'title' => 'Logging Out?',
            'description' => 'Are you sure you want to end your session?',
            'icon' => 'question',
            'accept' => [
                'label' => 'Yes, logout',
                'method' => 'logout',
                'params' => 'Logged out',
            ],
            'reject' => [
                'label' => 'No, cancel',
                'method' => 'cancel',
            ],
        ]);
    }

    public function cancel(): void
    {
        $this->notification()->info('Logout cancelled', 'You are still logged in.');
    }

    public function logout()
    {
        Auth::logout();

        // Invalidate the session to prevent session fixation attacks
        Session::invalidate();

        // Regenerate the CSRF token
        Session::regenerateToken();

        return $this->redirect('/login', navigate: true);
    }
};
?>

<div>
    <button wire:click="confirmLogout" class="w-full text-left flex items-center space-x-2 hover:bg-none cursor-pointer text-secondary hover:font-medium">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
            <path fill-rule="evenodd" d="M17 4.25A2.25 2.25 0 0 0 14.75 2h-5.5A2.25 2.25 0 0 0 7 4.25v2a.75.75 0 0 0 1.5 0v-2a.75.75 0 0 1 .75-.75h5.5a.75.75 0 0 1 .75.75v11.5a.75.75 0 0 1-.75.75h-5.5a.75.75 0 0 1-.75-.75v-2a.75.75 0 0 0-1.5 0v2A2.25 2.25 0 0 0 9.25 18h5.5A2.25 2.25 0 0 0 17 15.75V4.25Z" clip-rule="evenodd" />
            <path fill-rule="evenodd" d="M1 10a.75.75 0 0 1 .75-.75h9.546l-1.048-.943a.75.75 0 1 1 1.004-1.114l2.5 2.25a.75.75 0 0 1 0 1.114l-2.5 2.25a.75.75 0 1 1-1.004-1.114l1.048-.943H1.75A.75.75 0 0 1 1 10Z" clip-rule="evenodd" />
        </svg>
        <span class="">Logout</span>
    </button>
</div>