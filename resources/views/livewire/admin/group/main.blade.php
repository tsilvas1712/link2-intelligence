<div>
    <x-header title="Grupos" separator>
        <x-slot:actions>

            <x-button icon="o-plus" class="btn-primary" label="Criar novo grupo"
                link="{{ route('admin.groups.criar') }}" />
        </x-slot:actions>
    </x-header>

    <div>
        <x-table :headers="$headers" :rows="$this->grupos">
            @scope('actions', $grupo)
                <div class="flex gap-2">
                    <x-button icon="o-pencil" link="{{ route('admin.groups.editar', $grupo->id) }}" spinner
                        class="btn-sm btn-primary" />
                    <x-button icon="o-trash" wire:click="delete({{ $grupo->id }})" spinner class="btn-sm btn-primary" />
                </div>
            @endscope
        </x-table>

    </div>
</div>
