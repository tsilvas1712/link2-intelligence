<div>
    <x-header title="Vendedores" subtitle="Ajustes de Parâmetros" separator />
    <form wire:submit="import">
        <div class="flex justify-between bg-gray-200 rounded shadow p-2 items-center mb-8">

            <div>
                <x-input class="p-2" label="Atualizar Metas" placeholder="Digite o nome da filial" icon="o-table-cells"
                    type="file" wire:model="file" />
            </div>
            <div>
                <x-button class="btn-primary" icon="o-arrow-up-on-square" label="Upload" type="submit" />
            </div>

        </div>
    </form>

    <div>
        <x-input label="Busca" placeholder="Digite parte do nome ou cpf para ser feita a busca"
            icon="o-magnifying-glass" wire:model.live="search" />
        <x-table :headers="$this->headers" :rows="$this->getVendedores" with-pagination>
            @scope('actions', $vendedor)
                <x-button icon="o-eye" link="{{ route('admin.vendedores.show', $vendedor->id) }}" spinner
                    class="btn-sm btn-primary" />
            @endscope

        </x-table>

    </div>


</div>
