<div>
    <x-header title="Filiais" subtitle="Ajustes de Parâmetros" separator />

    <div>
        <x-input label="Busca" placeholder="Digite parte do nome da filial para ser feita a busca"
            icon="o-magnifying-glass" wire:model.live="search" />
        <x-table :headers="$this->headers" :rows="$this->getFiliais" with-pagination>
            @scope('actions', $filial)
                <x-button icon="o-eye" link="{{ route('admin.filiais.show', $filial->id) }}" spinner
                    class="btn-sm btn-primary" />
            @endscope

        </x-table>

    </div>


</div>
