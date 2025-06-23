<div>
    <x-header separator subtitle="Quadros e Categorias que aparecerão no Dash Principal" title="Categorias" />
    <div class="w-full flex bg-gray-200 rounded-lg p-4 justify-between gap-4">
        <div class="w-2/3">
            <x-input class="w-full" placeholder="Digite para buscar categoria"/>
        </div>
        <div class="w-1/3">
            <x-button wire:click="create" class="bg-primary w-full hover:bg-secondary text-white" icon="o-plus">
                 Adicionar Categoria
            </x-button>
        </div>
    </div>
    <x-table :headers="$this->headers()" :rows="$this->categories()"  >
        @scope('cell_created_at',$category)
            {{ $category->created_at->format('d/m/Y') }}
        @endscope
        @scope('cell_updated_at',$category)
            {{ $category->updated_at->format('d/m/Y') }}
        @endscope
        @scope('cell_active',$category)
        @if($category->active)
            <span class="text-green-500 font-bold">Ativo</span>
        @else
            <span class="text-red-500 font-bold">Inativo</span>
        @endif
        @endscope
        @scope('actions',$category)
            <div class="flex items-center justify-end gap-2">
                <x-button wire:click="edit({{ $category->id }})" class="btn-sm bg-primary hover:bg-secondary text-white" icon="o-pencil-square">
                    Editar
                </x-button>
            </div>
        @endscope




    </x-table>


</div>
