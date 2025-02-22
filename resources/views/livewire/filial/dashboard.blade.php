<div>
    <x-header title="{{ $filial->filial }}" subtitle="{{ $meses[$mes - 1]['name'] . '/' . $ano }}" separator>
        <x-slot:middle class="">
            <x-loading class="flex flex-col text-primary loading-lg loading-dots lg:flex-none" wire:loading />
            <div class="flex gap-2 !justify-end w-full flex-col lg:flex-row" wire:loading.remove>
                <x-select icon="o-calendar" placeholder="Selecione o Mês" :options="$meses" wire:model="mesSelecionado" />
                <x-select icon="o-calendar" placeholder="Selecione o Ano" :options="$anos" wire:model="anoSelecionado" />
            </div>

        </x-slot:middle>
        <x-slot:actions>
            <x-button icon="o-funnel" class="btn-primary" label="Filtrar" lazy wire:click="filter"
                wire:loading.remove />
        </x-slot:actions>
    </x-header>

    <div class="flex flex-col gap-4">

        <div class="grid w-full grid-cols-1 gap-2 rounded shadow md:grid-cols-2 lg:grid-cols-3">
            <div class="flex flex-col items-center gap-2 p-2 bg-white rounded">
                <span class="text-lg font-bold">Faturamento Total</span>
                <span class="text-2xl font-black">R$ {{ number_format($faturamentoTotal, 2, ',', '.') }}</span>
                <div class="flex justify-between w-full gap-2">
                    <div class="flex flex-col items-center w-2/5 p-2 rounded shadow bg-primary">
                        <span class="text-xs font-bold text-white">Tendência</span>
                        <span class="text-xs font-bold text-white">R$
                            {{ number_format($tendenciaFaturamento, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex flex-col items-center w-1/5 p-2">
                        @php
                            $meta =
                                $faturamentoTotal === 0 || $metas === null
                                    ? 0
                                    : ($faturamentoTotal * 100) / $metas->meta_faturamento;
                        @endphp
                        <span class="font-bold text-md">{{ number_format($meta, 2, ',', '.') }}%</span>
                        @if (floatVal($meta) >= 100.01)
                            <x-icon name="o-arrow-trending-up" class="w-6 h-6 text-green-500" />
                        @elseif (floatVal($meta) >= 80.0 && floatVal($meta) < 100.01)
                            <x-icon name="o-arrow-right" class="w-6 h-6 text-blue-500" />
                        @else
                            <x-icon name="o-arrow-trending-down" class="w-6 h-6 text-red-500" />
                        @endif
                    </div>
                    <div class="flex flex-col items-center w-2/5 p-2 bg-orange-400 rounded shadow">
                        <span class="text-xs font-bold text-white">Meta</span>
                        <span class="text-xs font-bold text-white">R$
                            {{ number_format($metas === null ? 0 : $metas->meta_faturamento, 2, ',', '.') }}</span>
                    </div>

                </div>

            </div>

            <div class="flex flex-col items-center gap-2 p-2 bg-white rounded">
                <span class="text-lg font-bold">Aparelhos Total</span>
                <span class="text-2xl font-black">R$ {{ number_format($aparelhosTotal, 2, ',', '.') }}</span>
                <div class="flex justify-between w-full gap-2">
                    <div class="flex flex-col items-center w-2/5 p-2 rounded shadow bg-primary">
                        <span class="text-xs font-bold text-white">Tendência</span>
                        <span class="text-xs font-bold text-white">R$
                            {{ number_format($tendenciaAparelhosTotal, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex flex-col items-center w-1/5 p-2">
                        @php
                            $meta =
                                $aparelhosTotal === 0 || !$metas ? 0 : ($aparelhosTotal * 100) / $metas->meta_aparelhos;
                        @endphp
                        <span class="font-bold text-md">{{ number_format($meta, 2, ',', '.') }}%</span>
                        @if (floatVal($meta) >= 100.01)
                            <x-icon name="o-arrow-trending-up" class="w-6 h-6 text-green-500" />
                        @elseif (floatVal($meta) >= 80.0 && floatVal($meta) < 100.01)
                            <x-icon name="o-arrow-right" class="w-6 h-6 text-blue-500" />
                        @else
                            <x-icon name="o-arrow-trending-down" class="w-6 h-6 text-red-500" />
                        @endif
                    </div>
                    <div class="flex flex-col items-center w-2/5 p-2 bg-orange-400 rounded shadow">
                        <span class="text-xs font-bold text-white">Meta</span>
                        <span class="text-xs font-bold text-white">R$
                            {{ number_format($metas === null ? 0 : $metas->meta_aparelhos, 2, ',', '.') }}</span>
                    </div>

                </div>

            </div>

            <div class="flex flex-col items-center gap-2 p-2 bg-white rounded">
                <span class="text-lg font-bold">Acessórios Total</span>
                <span class="text-2xl font-black">R$ {{ number_format($acessoriosTotal, 2, ',', '.') }}</span>
                <div class="flex justify-between w-full gap-2">
                    <div class="flex flex-col items-center w-2/5 p-2 rounded shadow bg-primary">
                        <span class="text-xs font-bold text-white">Tendência</span>
                        <span class="text-xs font-bold text-white">R$
                            {{ number_format($tendenciaAcessorioTotal, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex flex-col items-center w-1/5 p-2">
                        @php
                            $meta =
                                $acessoriosTotal === 0 || !$metas
                                    ? 0
                                    : ($acessoriosTotal * 100) / $metas->meta_acessorios;
                        @endphp
                        <span class="font-bold text-md">{{ number_format($meta, 2, ',', '.') }}%</span>
                        @if (floatVal($meta) >= 100.01)
                            <x-icon name="o-arrow-trending-up" class="w-6 h-6 text-green-500" />
                        @elseif (floatVal($meta) >= 80.0 && floatVal($meta) < 100.01)
                            <x-icon name="o-arrow-right" class="w-6 h-6 text-blue-500" />
                        @else
                            <x-icon name="o-arrow-trending-down" class="w-6 h-6 text-red-500" />
                        @endif
                    </div>
                    <div class="flex flex-col items-center w-2/5 p-2 bg-orange-400 rounded shadow">
                        <span class="text-xs font-bold text-white">Meta</span>
                        <span class="text-xs font-bold text-white">R$
                            {{ number_format($metas === null ? 0 : $metas->meta_acessorios, 2, ',', '.') }}</span>
                    </div>

                </div>

            </div>
        </div>

        <div class="flex flex-col gap-2 p-4 bg-white rounded shadow">
            <x-chart wire:model="chartVendasDiarias" />
            <x-chart wire:model="chartDiario" />
        </div>
        <div class="flex flex-col items-center w-full gap-4 p-2 bg-white rounded shadow">
            <span class="text-3xl italic font-bold">Progressão Mensal</span>
            <div class="w-full ">
                <livewire:filiais.chart.progressao-mensal :data="$chartProgressao" />
            </div>
        </div>
        <div class="flex flex-col w-full gap-2 lg:flex-row">
            <div class="w-full p-2 bg-white rounded shadow lg:w-1/2">
                <livewire:filiais.chart.aparelhos :data="$chartAparelhos" />
            </div>
            <div class="w-full p-2 bg-white rounded shadow lg:w-1/2">
                <livewire:filiais.chart.acessorios :data="$chartAcessorios" />
            </div>


        </div>

        <div class="flex flex-col w-full gap-4 p-2 bg-white rounded shadow">
            <span class="w-full text-xl italic font-bold text-center">Grupos de Planos</span>
            <div class="flex flex-col justify-center gap-4 lg:flex-row">
                @foreach ($planos as $plano)
                    <div class="flex flex-col items-center w-full gap-4 p-2 bg-gray-100 rounded shadow lg:w-1/3">
                        <a href="{{ route('detalhes.grupos', $plano['id']) }}"
                            class="flex flex-col items-center w-full">
                            <span class="text-lg font-bold">{{ $plano['grupo'] }}</span>
                            <div class="flex flex-row justify-between w-full gap-4">
                                <div class="flex flex-col items-center w-full gap-2">
                                    <span>Total Plano</span>
                                    <span class="w-full p-2 text-xs font-bold bg-white rounded shadow">R$
                                        {{ number_format($plano['total'], 2, ',', '.') }}</span>
                                    <span class="w-full p-2 text-xs font-bold bg-orange-200 rounded shadow">R$
                                        {{ number_format($plano['meta_plano'], 2, ',', '.') }}</span>
                                </div>
                                <div class="flex flex-col items-center w-full gap-2">
                                    <span>Total Gross</span>
                                    <span
                                        class="w-full p-2 text-xs font-bold bg-white rounded shadow">{{ $plano['gross'] }}</span>
                                    <span
                                        class="w-full p-2 text-xs font-bold bg-orange-200 rounded shadow">{{ $plano['meta_gross'] }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

            </div>
            <div class="flex flex-col w-full gap-4 lg:flex-row">
                <div class="flex flex-col items-center w-full lg:w-1/2">
                    <span class="text-xl italic font-bold text-center">Vendas</span>
                    <livewire:charts.produtos :data="$chartPlanosValor" />
                </div>
                <div class="flex flex-col items-center w-full lg:w-1/2">
                    <span class="text-xl italic font-bold text-center">Gross</span>
                    <livewire:charts.produtos-gross :data="$chartPlanosGross" />
                </div>

            </div>


        </div>
        <div class="w-full border-b-2 border-primary">
            <span class="text-xl font-bold text-center ">Ranking</span>
        </div>
        <div class="flex flex-col gap-4 lg:flex-row ">
            <div class="flex flex-col items-center justify-center w-full max-h-screen lg:w-1/2">
                <livewire:filiais.chart.fabricante :data="$chartFabricante" />
            </div>
            <div class="flex flex-col items-center justify-center w-full p-2 bg-white rounded shadow lg:w-1/2">
                <span class="text-xl italic font-bold">Ranking Vendedores</span>
                <div class="w-full ">
                    <x-tabs wire:model="selectedTabV">
                        <x-tab name="vendedores-up" label="10" icon="s-arrow-trending-up">
                            <div class="w-full ">
                                <livewire:charts.ranking-vendedores :data="$chartVendedores" />
                            </div>
                        </x-tab>
                        <x-tab name="vendedores-down" label="10" icon="s-arrow-trending-down">
                            <div class="w-full ">
                                <livewire:charts.ranking-vendedores-down :data="$chartVendedoresDown" />
                            </div>
                        </x-tab>
                    </x-tabs>
                </div>
            </div>
        </div>

        <div class="w-full border-b-2 border-primary">
            <span class="text-xl font-bold text-center ">Vendedores</span>
        </div>

        <div class="grid grid-cols-2 gap-2 lg:grid-cols-4">
            @foreach ($vendedores as $vendedor)
                <div class="w-full p-4 bg-white rounded shadow hover:bg-secondary">
                    <a href="{{ route('vendedor.dashboard', $vendedor['id']) }}">
                        <div class="flex justify-between">
                            <span class="text-sm font-bold">{{ $vendedor['vendedor'] }}</span>
                            @if ($vendedor['status'] === 'up')
                                <x-icon name="o-arrow-trending-up" class="w-6 h-6 text-green-500" />
                            @endif

                            @if ($vendedor['status'] === 'down')
                                <x-icon name="o-arrow-trending-down" class="w-6 h-6 text-red-500" />
                            @endif

                            @if ($vendedor['status'] === 'ok')
                                <x-icon name="o-arrow-right" class="w-6 h-6 text-blue-500" />
                            @endif
                        </div>
                        <di class="flex flex-col items-center gap-2">
                            <span class="font-bold">R$
                                {{ number_format($vendedor['total'], 2, ',', '.') }}</span>
                            <div class="flex flex-col justify-between w-full gap-2 lg:flex-row">
                                <span class="p-2 text-xs font-bold text-center bg-blue-200 shadow rounded-xl">R$
                                    {{ number_format($vendedor['total'], 2, ',', '.') }}</span>
                                <span class="p-2 text-xs font-bold text-center bg-orange-200 shadow rounded-xl">R$
                                    {{ number_format($vendedor['metas'], 2, ',', '.') }}</span>
                            </div>

                        </di>
                    </a>
                </div>
            @endforeach

        </div>
    </div>

</div>
