<div>
    <x-header title="{{ $filial->filial }}" separator>
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

        <div class="grid grid-cols-4 gap-2">
            @foreach ($vendedores as $vendedor)
                <div class="bg-white rounded shadow p-4 w-full hover:bg-secondary">
                    <a href="{{ route('vendedor.dashboard', $vendedor->vendedor_id) }}">
                        <h2 class="text-md font-semibold">{{ $vendedor->vendedor->nome }}</h2>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Total de Vendas</span>
                            <span class="text-xs font-semibold">R$
                                {{ number_format($vendedor->total_caixa, 2, ',', '.') }}</span>
                        </div>
                    </a>
                </div>
            @endforeach

        </div>
    </div>

</div>
