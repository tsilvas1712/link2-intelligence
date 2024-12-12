<div>
    <x-header title="{{ $vendedor->nome }}" separator>
        <x-slot:middle class="!justify-end">
            <div class="flex gap-2">
                <x-select icon="o-calendar" placeholder="Selecione o Mês" :options="$meses" wire:model="mesSelecionado" />
                <x-select icon="o-calendar" placeholder="Selecione o Ano" :options="$anos" wire:model="anoSelecionado" />
            </div>

        </x-slot:middle>
        <x-slot:actions>
            <x-button icon="o-funnel" class="btn-primary" label="Filtrar" wire:click="filter" />
        </x-slot:actions>
    </x-header>

    <div class="flex flex-col gap-4">
        <div class="bg-white rounded shadow p-4">
            <x-chart wire:model="chartVendasDiarias" />
        </div>
        <div class="flex gap-2 w-full">
            <div class="bg-white rounded shadow p-2 w-full">
                <x-chart wire:model="chartAparelhos" />
            </div>
            <div class="bg-white rounded shadow p-2 w-full">
                <x-chart wire:model="chartAcessorios" />
            </div>


        </div>


    </div>

</div>
