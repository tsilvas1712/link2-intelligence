<?php

namespace App\Livewire\Admin\Group;

use App\Models\Grupo;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Main extends Component
{
    use WithPagination,Toast;
    public $headers = [
        ['key' => 'id', 'label' => '#'],
        ['key' => 'nome', 'label' => 'Grupo'],
        ['key' => 'category_id', 'label' => 'Categoria'],
        ['key' => 'principal', 'label' => 'Destaque'],
        ['key' => 'created_at', 'label' => 'Criado em'],
        ['key' => 'updated_at', 'label' => 'Atualizado em'],
    ];

    public function render()
    {
        return view('livewire.admin.group.main');
    }

    #[Computed]
    public function grupos(): LengthAwarePaginator
    {
        return Grupo::query()->paginate();
    }

    public function delete($id)
    {
        $grupo = Grupo::findOrFail($id);
        $grupo->delete();
        $this->info(
            title: 'Grupo deletado com sucesso',
            position: 'toast-top toast-end',
            icon: 'o-information-circle',
            css: 'alert-info',
            timeout: 3000,
            redirectTo: null
        );

    }

}
