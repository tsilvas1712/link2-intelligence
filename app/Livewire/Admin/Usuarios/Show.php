<?php

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
use Livewire\Component;
use PhpParser\Node\Expr\Cast\Object_;

class Show extends Component
{
    public $user;
    public $cargoSelected;


    public function mount($id = null)
    {
        if ($id) {
            $user = User::find($id);
            $this->user["name"] = $user->name;
            $this->user["email"] = $user->email;
            $this->user["cargo"] = $user->cargo;
            $this->user["password"] = $user->password;
        } else {
            $this->user = [
                "name" => null,
                "email" => null,
                "cargo" => null,
                "password" => null
            ];
        }
    }
    public function render()
    {
        return view('livewire.admin.usuarios.show');
    }

    public function save()
    {

        $isUserExisted = User::where('email', $this->user['email'])->first();

        if ($isUserExisted) {
            $this->addError('invalidRegister', 'Usuário já cadastrado');
        }

        if ($this->user['password'] !== $this->user['password_confirmation']) {
            $this->addError('invalidRegister', 'Confirmação de senha não confere');
        }

        User::create(
            [
                'name' => $this->user['name'],
                'email' => $this->user['email'],
                'password' => bcrypt($this->user['password']),
                'cargo' => $this->cargoSelected
            ]

        );

        redirect()->route('admin.usuarios');
    }

    public function update()
    {
        $user = User::find($this->user['id']);

        $user->name = $this->user['name'];
        $user->email = $this->user['email'];
        $user->cargo = $this->cargoSelected;
        $user->password = bcrypt($this->user['password']);
        $user->save();
    }
}
