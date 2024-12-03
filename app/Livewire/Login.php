<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Login extends Component
{
    public string $email;
    public string $password;

    #[Layout('components.layouts.auth')]
    public function render()
    {
        return view('livewire.login');
    }

    public function login()
    {
        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {

            $this->addError('invalidCredentials', trans('auth.failed'));

            return;
        }

        $this->redirect(route('dashboard'));
    }
}
