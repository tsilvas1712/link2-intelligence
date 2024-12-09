<div>
    <x-header title="Vendedores" subtitle="Ajustes de Parâmetros" separator />

    <div>
        <x-input label="Busca" placeholder="Digite parte do nome ou cpf para ser feita a busca" icon="o-magnifying-glass"
            wire:model.live="search" />
        <x-table :headers="$this->headers" :rows="$this->getVendedores" with-pagination>
            @scope('actions', $vendedor)
                <x-button icon="o-eye" link="{{ route('admin.vendedores.show', $vendedor->id) }}" spinner
                    class="btn-sm btn-primary" />
            @endscope

        </x-table>

    </div>


</div>
