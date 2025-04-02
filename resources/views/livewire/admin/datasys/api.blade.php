<div>
    <x-header separator subtitle="Chave API" title="Datasys" />
    <div class="w-full">
        <x-form wire:submit="save">
            <x-input label="Chave API" wire:model="datasys_key" />
            <x-datetime label="Data de Expiração" wire:model="datasys_validate" />
            <x-slot:actions>
                <div class="w-1/3">
                    <x-button class="btn-primary w-full" label="Salvar" spinner="save" type="submit" />
                </div>
            </x-slot:actions>
        </x-form>
    </div>

</div>
