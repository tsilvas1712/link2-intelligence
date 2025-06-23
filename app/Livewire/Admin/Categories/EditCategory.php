<?php

namespace App\Livewire\Admin\Categories;

use Illuminate\Support\Str;
use Livewire\Component;

class EditCategory extends Component
{
    use \Mary\Traits\Toast;

    public $name;
    public $slug;
    public $description;
    public $order;
    public $active = true;
    public $category_id;


    public function mount(int $id):void
    {
        $category = \App\Models\Category::query()->where('id',$id)->first();
        $this->category_id = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->order = $category->order;
        $this->active = $category->active;
    }
    public function render():\Illuminate\View\View
    {
        return view('livewire.admin.categories.edit-category');
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

        $category = \App\Models\Category::query()->where('id', $this->category_id)->first();
        $category->name = $this->name;
        $category->slug = Str::slug($this->name);
        $category->description = $this->description;
        $category->order = $this->order;
        $category->active = $this->active;
        $category->save();

        $this->success(
            title: 'Categoria salva com sucesso',
            position: 'toast-top toast-end',
            icon: 'o-check-circle',
            css: 'alert-success',
            timeout: 3000,
            redirectTo: route('admin.categorias')
        );
    }
}
