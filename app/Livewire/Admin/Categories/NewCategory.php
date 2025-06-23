<?php

namespace App\Livewire\Admin\Categories;

use Illuminate\Support\Str;
use Livewire\Component;
use Mary\Traits\Toast;

class NewCategory extends Component
{
    use Toast;
    public $name;
    public $description;
    public $order;
    public $active = true;

    public function render()
    {
        return view('livewire.admin.categories.new-category');
    }

    public function save():void
    {
        $category_exists = \App\Models\Category::where('name', $this->name)->first();
        if ($category_exists) {
            $this->error(
                title: 'Categoria já existe',
                position: 'toast-top toast-end',
                icon: 'o-exclamation-circle',
                css: 'bg-red-500',
                timeout: 3000
            );
            return;
        }

        $category = new \App\Models\Category();
        $category->name = $this->name;
        $category->slug = Str::slug($this->name);
        $category->description = $this->description;
        $category->order = $this->order;
        $category->active = $this->active;
        $category->save();

        $this->success(
                title: 'Categoria criada com sucesso',
                position: 'toast-top toast-end',
                icon: 'o-check-circle',
                css: 'alert-success',
                timeout: 3000,
                redirectTo: route('admin.categorias')
        );
    }
}
