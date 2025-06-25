<?php

namespace App\Livewire\Admin\Categories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Route;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Main extends Component
{
    use WithPagination;

    public function render():\Illuminate\View\View
    {
        return view('livewire.admin.categories.main');
    }

    public function create()
    {
        return redirect()->route('admin.categorias.new');
    }

    public function headers():array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Nome'],
            ['key' => 'description', 'label' => 'Descrição'],
            ['key' => 'order', 'label' => 'Ordem'],
            ['key' => 'active', 'label' => 'Ativo'],
            ['key' => 'created_at', 'label' => 'Criado em'],
        ];
    }

    #[Computed]
    public function categories():LengthAwarePaginator
    {
        return \App\Models\Category::query()
            ->orderBy('order', 'asc')
            ->paginate(10);
    }

    public function edit(int $id)
    {
        return redirect()->route('admin.categorias.edit', ['id' => $id]);
    }
}
