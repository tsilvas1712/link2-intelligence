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
                    <x-textarea label="Modalidade de Vendas" wire:model="modalidades_vendas"
                        placeholder="Separar as modalidades de vendas por ';'" hint="Max 1000 chars" rows="5"
                        inline />
                    <x-textarea label="Planos Habilitados" wire:model="planos_habilitados"
                        placeholder="Separar os Planos Habilitados por ';'" hint="Max 1000 chars" rows="5"
                        inline />

                    <x-input label="Grupo de Estoque" wire:model="grupo_estoque" />
                    <x-input label="Campo de Referência" wire:model="campo_valor" />

                    <x-slot:actions>
                        <x-button label="Salvar" class="btn-primary w-full" type="submit" spinner="save" />
                    </x-slot:actions>
                </x-form>
            </div>

        </div>
    </div>
</div>
