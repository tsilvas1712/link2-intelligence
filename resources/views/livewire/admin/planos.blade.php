<div>
    <x-header title="Planos" separator>
        <x-slot:actions>
            <x-button icon="o-plus" class="btn-primary" label="Criar novo Plano" wire:click='openModal' />
        </x-slot:actions>
    </x-header>
    <form wire:submit="import">
        <div class="flex items-center justify-between p-2 mb-8 bg-gray-200 rounded shadow">

            <div>
                <x-input class="p-2" label="Atualizar Valores de Planos" icon="o-table-cells" type="file"
                    wire:model="file" />
            </div>
            <div>
                <x-button class="btn-primary" icon="o-arrow-up-on-square" label="Upload" type="submit" />
            </div>

        </div>
    </form>
    <div>
        <x-input label="Busca" placeholder="Digite o plano habilitado para ser feita a busca" icon="o-magnifying-glass"
            wire:model.live="search" />
        <x-table :headers="$this->headers" :rows="$this->getPlanos" with-pagination>
            @scope('cell_valor', $plano)
                R$ {{ number_format($plano->valor, 2, ',', '.') }}
            @endscope
            @scope('actions', $plano)
                <x-button icon="o-eye" @click="$wire.openModal({{ $plano->id }})" spinner class="btn-sm btn-primary" />
            @endscope

        </x-table>

    </div>


    <x-modal wire:model="modal" class="p-8 backdrop-blur" persistent>
        <div class="flex flex-col gap-4 my-4">
            <span
                class="font-sans text-xl italic font-bold text-primary">{{ $plano->plano_habilitado ?? 'Novo Plano' }}</span>

            <div class="w-full">
                <x-input label="Valor Franquia" wire:model.live="valor_franquia" prefix="R$" locale="pt-BR" />
            </div>


        </div>


        <div class="flex w-full gap-4">
            <div class="w-1/2">
                <x-button label="Cancelar" @click="$wire.closeModal" class="w-full" />
            </div>
            <div class="w-1/2">
                <x-button label="Salvar" @click="$wire.closeModal" class="w-full btn btn-primary" />
            </div>

        </div>
    </x-modal>

</div>
