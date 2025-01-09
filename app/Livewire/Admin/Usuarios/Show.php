<?php

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
use Livewire\Component;
use PhpParser\Node\Expr\Cast\Object_;

class Show extends Component
{
    public $user;
    public $name;
    public $email;
    public $password;

    public $password_confirmation;
    public $cargoSelected;

    public $showModal;


    public function mount($id = null)
    {
        $this->showModal = false;
        if ($id) {
            $user = User::find($id);
            $this->user = $user;
            $this->name = $user->name;
            $this->email = $user->email;

            $this->cargoSelected = $user->cargo;
        }
    }
    public function render()
    {
        return view('livewire.admin.usuarios.show');
    }

    public function save()
    {
        $validate = $this->validate([
            'name' => 'min:3',
            'email' => 'email',
            'cargoSelected' => 'required'
        ]);

        if ($this->user === null) {
            $isUserExisted = User::where('email', $validate['email'])->first();

            if ($isUserExisted) {
                $this->addError('invalidRegister', 'Usuário já cadastrado');
            }

            User::create([
                'name' => $validate['name'],
                'email' => $validate['email'],
                'cargo' => $validate['cargoSelected']
            ]);

            return redirect()->route('admin.usuarios');
        }

        $this->user->update([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'cargo' => $validate['cargoSelected']
        ]);

        redirect()->route('admin.usuarios');
    }

    public function update()
    {
        $user = User::find($this->user['id']);

        $user->name = $this->user['name'];
        $user->email = $this->user['email'];
        $user->cargo = $this->cargoSelected;
        //$user->password = bcrypt($this->user['password']);
        $user->save();
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function updatePassword()
    {
        $user = User::find($this->user->id);
        $user->password = bcrypt($this->password);
        $user->update();

        redirect()->route('admin.usuarios');
    }
}
