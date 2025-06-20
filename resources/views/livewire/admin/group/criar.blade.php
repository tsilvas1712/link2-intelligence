<div>
    <x-header title="Criar Novo Grupo" separator>
        <x-slot:actions>

            <x-button icon="o-arrow-left" class="btn-primary" label="Voltar para Grupos"
                link="{{ route('admin.groups') }}" />
        </x-slot:actions>
    </x-header>

    <div class="flex flex-col w-full ">
        <div class="flex gap-4">
            <div class="w-full">
                <x-form wire:submit="save">
                    <x-input label="Nome do Grupo" wire:model="nome" />
                    <x-input label="Descrição" wire:model="descricao" />
                    <x-choices label="Grupos De Estoques" wire:model="selected_grupo_estoque" :options="$this->getGrupoEstoque()" placeholder="Buscar..."  >
                    </x-choices>

                    <x-choices label="Modalidade de Vendas" wire:model="selected_modalidade_vendas" :options="$this->getModalidadeVendas()" placeholder="Buscar..."  >
                    </x-choices>

                    <x-choices label="Planos Habilitados" wire:model="selected_plano_habilitados" :options="$this->getPlanoHabilitados()" placeholder="Buscar..."  >
                    </x-choices>

                    <x-input label="Campo de Referência" wire:model="campo_valor" />

                    <x-slot:actions>
                        <x-button label="Salvar" class="btn-primary w-full" type="submit" spinner="save" />
                    </x-slot:actions>
                </x-form>
            </div>

        </div>
    </div>
</div>
