<div>
    <x-header separator  title="{{'Editar Categoria '.$name}}" />
    <form class="flex gap-4 flex-col" wire:submit="save">
        <x-input wire:model="name" class="w-full" placeholder="Nome da Categoria" label="Nome da Categoria" />
        <x-input wire:model="description" class="w-full" placeholder="Descrição da Categoria" label="Descrição da Categoria" />
        <div class="flex items-center justify-between gap-4">
            <x-toggle wire:model="active" label="Ativo" class="mt-4" />
            <x-input wire:model="order" class="w-full" placeholder="Ordem de Exibição" label="Ordem de Exibição" type="number" />
        </div>
        <x-menu-separator/>
        <div class="flex items-center justify-between gap-4">
            <a href="{{route('admin.categorias')}}" class="bg-gray-400 btn w-1/3 hover:bg-secondary text-white">
               <x-icon  name="o-arrow-left"/> Voltar
            </a>
            <x-button wire:click="save" class="bg-primary w-1/3 hover:bg-secondary text-white" icon="o-check">
                Salvar Categoria
            </x-button>
        </div>
    </form>
</div>
