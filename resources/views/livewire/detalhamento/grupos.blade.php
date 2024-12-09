<div class="w-full">
    <x-header title="{{ $grupo->nome }}" subtitle="{{ $grupo->descricao . ' - ' . $mes . '/' . $ano }}" separator />
    <div class="flex justify-between gap-2 h-full items-center bg-white rounded shadow p-2">
        <div class="flex w-2/4 gap-2">
            <div class="w-full">
                <x-select label="Selecione o Mês" placeholder="Escolha um mês" icon="o-calendar" :options="$meses"
                    wire:model="monthSelected" />
            </div>
            <div class="w-2/4">
                <x-select label="Selecione o Ano" placeholder="Escolha um mês" icon="o-calendar" :options="$anos"
                    wire:model="yearSelected" />
            </div>
        </div>
        <div class="flex flex-col justify-center items-center h-full">
            <x-button label="Filtrar" class="btn-primary" wire:click="filter" />
        </div>
    </div>

    <div class="w-full bg-white p-2 rounded shadow mt-2">
        <x-chart wire:model="chartVendas" />
    </div>

    <div class="w-full bg-white p-2 rounded shadow mt-2">
        <x-chart wire:model="chartGross" />
    </div>

</div>
