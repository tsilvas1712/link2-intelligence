<div>
    <x-header title="Usuários" subtitle="Ajustes de usuários do Sistema" separator />
    <div>
        <div class="flex justify-between gap-8">
            <x-input label="Busca" placeholder="Digite parte do nome do usuário para ser feita a busca"
                icon="o-magnifying-glass" wire:model.live="search" />
            <x-button label="Novo Usuário" class="btn-primary" link="{{ route('admin.usuarios.show') }}" />
        </div>

        <x-table :headers="$this->headers" :rows="$this->getUsuarios" with-pagination>

            @scope('actions', $usuario)
                <x-button icon="o-eye" spinner class="btn-sm btn-primary"
                    link="{{ route('admin.usuarios.show', $usuario->id) }}" />
            @endscope

        </x-table>
    </div>

</div>
