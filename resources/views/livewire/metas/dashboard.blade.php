<div class="w-full">
    <x-header title="Dashboard" subtitle="{{ $meses[$mes - 1]['name'] . '/' . $ano }}" separator>
        <x-slot:middle>
            <div class="flex flex-col items-center w-full gap-2 lg:flex-row">
                <x-loading class="text-primary loading-lg loading-dots" wire:loading />
                <div class="w-full" wire:loading.remove>
                    <x-choices label="Filiais" wire:model="filiais_id" :options="$this->getFiliais()">
                        @scope('item', $filial)
                            <x-list-item :item="$filial" sub-value="bio">
                                <x-slot:avatar>
                                    <x-icon name="o-home" class="w-8 p-2 bg-orange-100 rounded-full h8" />
                                </x-slot:avatar>
                            </x-list-item>
                        @endscope
                    </x-choices>
                </div>
                <div class="flex flex-row items-center w-full gap-2" wire:loading.remove>
                    <x-select label="Mês" icon="o-calendar" placeholder="Selecione o Mês" :options="$meses"
                        wire:model="mesSelecionado" class="w-2/4" />
                    <x-select label="Ano" icon="o-calendar" placeholder="Selecione o Ano" :options="$anos"
                        wire:model="anoSelecionado" class="w-1/4" />
                </div>
            </div>

        </x-slot:middle>
        <x-slot:actions>
            <x-button icon="o-funnel" class="btn-primary" label="Filtrar" spinner wire:click="filter" />
        </x-slot:actions>

    </x-header>

    @if ($metas === null)
        <div class="flex flex-col items-center justify-center w-full h-96">
            <x-icon name="o-arrow-right" class="w-24 h-24 text-gray-400" />
            <span class="text-lg font-bold text-gray-400">Nenhuma meta cadastrada para o mês e ano selecionado</span>
        </div>
    @endif

    <div wire:init="init">
        @if ($metas)
            <div class="flex flex-col w-full gap-4 ">
                <div class="grid w-full grid-cols-1 gap-2 rounded shadow lg:grid-cols-3">
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
                                    $meta = number_format(
                                        $faturamentoTotal === 0 || $metas[0]['meta_faturamento'] === null
                                            ? 0
                                            : ($faturamentoTotal / $metas[0]['meta_faturamento']) * 100,
                                        2,
                                        ',',
                                        '.',
                                    );
                                @endphp
                                <span class="text-lg font-bold">{{ $meta }}%</span>
                                @if (floatVal($meta) > 100.0)
                                    <x-icon name="o-arrow-trending-up" class="w-6 h-6 text-green-500" />
                                @elseif (floatVal($meta) > 65.0 && floatVal($meta) < 100.0)
                                    <x-icon name="o-arrow-right" class="w-6 h-6 text-blue-500" />
                                @else
                                    <x-icon name="o-arrow-trending-down" class="w-6 h-6 text-red-500" />
                                @endif
                            </div>
                            <div class="flex flex-col items-center w-2/5 p-2 bg-orange-400 rounded shadow">
                                <span class="text-xs font-bold text-white">Meta</span>
                                <span class="text-xs font-bold text-white">R$
                                    {{ number_format($metas[0]['meta_faturamento'], 2, ',', '.') }}</span>
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
                                    $meta = number_format(
                                        $aparelhosTotal === 0 || $metas[0]['meta_aparelhos'] === null
                                            ? 0
                                            : ($aparelhosTotal * 100) / $metas[0]['meta_aparelhos'],
                                        2,
                                        ',',
                                        '.',
                                    );
                                @endphp
                                <span class="text-lg font-bold">{{ $meta }}%</span>
                                @if (floatVal($meta) > 100.0)
                                    <x-icon name="o-arrow-trending-up" class="w-6 h-6 text-green-500" />
                                @elseif (floatVal($meta) > 65.0 && floatVal($meta) < 100.0)
                                    <x-icon name="o-arrow-right" class="w-6 h-6 text-blue-500" />
                                @else
                                    <x-icon name="o-arrow-trending-down" class="w-6 h-6 text-red-500" />
                                @endif
                            </div>
                            <div class="flex flex-col items-center w-2/5 p-2 bg-orange-400 rounded shadow">
                                <span class="text-xs font-bold text-white">Meta</span>
                                <span class="text-xs font-bold text-white">R$
                                    {{ number_format($metas[0]['meta_aparelhos'], 2, ',', '.') }}</span>
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
                                    $meta = number_format(
                                        $acessoriosTotal === 0 || $metas[0]['meta_acessorios'] === null
                                            ? 0
                                            : ($acessoriosTotal * 100) / $metas[0]['meta_acessorios'],
                                        2,
                                        ',',
                                        '.',
                                    );
                                @endphp
                                <span class="text-lg font-bold">{{ $meta }}%</span>
                                @if (floatVal($meta) > 100.0)
                                    <x-icon name="o-arrow-trending-up" class="w-6 h-6 text-green-500" />
                                @elseif (floatVal($meta) > 65.0 && floatVal($meta) < 100.0)
                                    <x-icon name="o-arrow-right" class="w-6 h-6 text-blue-500" />
                                @else
                                    <x-icon name="o-arrow-trending-down" class="w-6 h-6 text-red-500" />
                                @endif
                            </div>
                            <div class="flex flex-col items-center w-2/5 p-2 bg-orange-400 rounded shadow">
                                <span class="text-xs font-bold text-white">Meta</span>
                                <span class="text-xs font-bold text-white">R$
                                    {{ number_format($metas[0]['meta_acessorios'], 2, ',', '.') }}</span>
                            </div>

                        </div>

                    </div>
                </div>



                <!-- CHARTS RANKING E FABRICANTES -->
                <div class="flex flex-col gap-2 ">
                    <div class="flex flex-col items-center w-full gap-4 p-2 bg-white rounded shadow">
                        <span class="text-3xl italic font-bold">Total de {{ $ano }}</span>
                        <div class="w-full ">
                            <livewire:charts.apex-bars :data="$chartMetas" />
                        </div>
                    </div>
                    <div class="flex flex-col h-full gap-2 lg:flex-row">
                        <div class="flex flex-col w-full gap-2 lg:w-1/2">
                            <div class="flex flex-col items-center w-full gap-4 p-2 bg-white rounded shadow">
                                <div class="flex gap-2">
                                    <span class="text-3xl italic font-bold">Ranking de Filiais</span>

                                </div>
                                <x-tabs wire:model="selectedTab">
                                    <x-tab name="filial-up" label="10" icon="s-arrow-trending-up">
                                        <div class="w-full ">
                                            <livewire:charts.ranking-filiais :data="$chartFiliais" />
                                        </div>
                                    </x-tab>
                                    <x-tab name="filial-down" label="10" icon="s-arrow-trending-down">
                                        <div class="w-full ">
                                            <livewire:charts.ranking-filiais-down :data="$chartFiliaisDown" />
                                        </div>
                                    </x-tab>
                                </x-tabs>

                            </div>

                            <div class="flex flex-col items-center w-full gap-4 p-2 bg-white rounded shadow">
                                <span class="text-3xl italic font-bold">Ranking de Vendedores</span>
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
                        <div class="flex flex-col items-center justify-center w-full max-h-screen lg:w-1/2">
                            <livewire:charts.apex-pie :data="$chartFabricante" />
                        </div>

                    </div>

                </div>

                <div class="flex flex-col w-full gap-4 p-2 bg-white rounded shadow">
                    <span class="w-full text-xl italic font-bold text-center">Produtos
                        {{ $ano }}</span>
                    <div class="flex flex-col justify-center gap-4 lg:flex-row">
                        @if ($planos)
                            @foreach ($planos as $plano)
                                <div
                                    class="flex flex-col items-center w-full gap-4 p-2 bg-gray-100 rounded shadow lg:w-1/3">
                                    <a href="{{ route('detalhes.grupos', $plano['id']) }}"
                                        class="flex flex-col items-center w-full">
                                        <span class="text-lg font-bold">{{ $plano['grupo'] }}</span>
                                        <div class="flex flex-row justify-between w-full gap-4">
                                            <div class="flex flex-col items-center w-full gap-2">
                                                <span>Total Plano</span>
                                                <span class="w-full p-2 text-xs font-bold bg-white rounded shadow">R$
                                                    {{ number_format($plano['total'], 2, ',', '.') }}</span>
                                                <span
                                                    class="w-full p-2 text-xs font-bold bg-orange-200 rounded shadow">R$
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
                        @endif

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


                <!-- FILIAIS -->
                <div class="flex gap-4">
                    <div class="grid w-full grid-cols-2 gap-2 lg:grid-cols-4 ">
                        @if ($filiais)
                            @foreach ($filiais as $filial)
                                <a href="{{ route('filial.dashboard', $filial['id']) }}">
                                    <div class="p-2 bg-white rounded shadow hover:bg-secondary">
                                        <div class="flex justify-between">
                                            <span class="text-sm font-bold">{{ $filial['filial'] }}</span>
                                            @if ($filial['status'] === 'up')
                                                <x-icon name="o-arrow-trending-up" class="w-6 h-6 text-green-500" />
                                            @endif

                                            @if ($filial['status'] === 'down')
                                                <x-icon name="o-arrow-trending-down" class="w-6 h-6 text-red-500" />
                                            @endif

                                            @if ($filial['status'] === 'ok')
                                                <x-icon name="o-arrow-right" class="w-6 h-6 text-blue-500" />
                                            @endif
                                        </div>

                                        <di class="flex flex-col items-center gap-2">
                                            <span class="font-bold">R$
                                                {{ number_format($filial['faturamento'], 2, ',', '.') }}</span>
                                            <div class="flex flex-col justify-between w-full gap-2 lg:flex-row">
                                                <span
                                                    class="p-2 text-xs font-bold text-center bg-blue-200 shadow rounded-xl">R$
                                                    {{ number_format($filial['tendencia'], 2, ',', '.') }}</span>
                                                <span
                                                    class="p-2 text-xs font-bold text-center bg-orange-200 shadow rounded-xl">R$
                                                    {{ number_format($filial['meta'], 2, ',', '.') }}</span>
                                            </div>

                                        </di>

                                    </div>
                                </a>
                            @endforeach
                        @endif
                    </div>


                </div>
            </div>
        @endif
    </div>






</div>
