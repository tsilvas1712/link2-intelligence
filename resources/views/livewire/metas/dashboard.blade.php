<div class="w-full">

    <x-header title="Dashboard" subtitle="{{ $meses[$mes - 1]['name'] . '/' . $ano }}" separator>

        <x-slot:middle>
            <div class="flex flex-col lg:flex-row gap-2 w-full items-center">
                <x-loading class="text-primary loading-lg loading-dots" wire:loading />

                <div class="w-full" wire:loading.remove>
                    <x-choices label="Filiais" wire:model="filiais_id" :options="$this->getFiliais()">
                        @scope('item', $filial)
                            <x-list-item :item="$filial" sub-value="bio">
                                <x-slot:avatar>
                                    <x-icon name="o-home" class="bg-orange-100 p-2 w-8 h8 rounded-full" />
                                </x-slot:avatar>
                            </x-list-item>
                        @endscope
                    </x-choices>
                </div>
                <div class="flex w-full flex-row gap-2 items-center" wire:loading.remove>
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

    <div class="flex flex-col w-full gap-4 ">

        <div class="grid grid-cols-1 lg:grid-cols-3 w-full rounded shadow gap-2">
            <div class="bg-white rounded p-2 flex flex-col items-center  gap-2">
                <span class="text-lg font-bold">Faturamento Total</span>
                <span class="text-2xl font-black">R$ {{ number_format($faturamentoTotal, 2, ',', '.') }}</span>
                <div class="flex justify-between gap-2 w-full">
                    <div class="bg-primary rounded shadow flex flex-col items-center p-2 w-2/5">
                        <span class="text-xs font-bold text-white">Tendência</span>
                        <span class="text-xs font-bold text-white">R$
                            {{ number_format($tendenciaFaturamento, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex flex-col items-center p-2 w-1/5">
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
                    <div class="bg-orange-400 rounded shadow flex flex-col items-center p-2 w-2/5">
                        <span class="text-xs font-bold text-white">Meta</span>
                        <span class="text-xs font-bold text-white">R$
                            {{ number_format($metas[0]['meta_faturamento'], 2, ',', '.') }}</span>
                    </div>

                </div>

            </div>

            <div class="bg-white rounded p-2 flex flex-col items-center  gap-2">
                <span class="text-lg font-bold">Aparelhos Total</span>
                <span class="text-2xl font-black">R$ {{ number_format($aparelhosTotal, 2, ',', '.') }}</span>
                <div class="flex justify-between gap-2 w-full">
                    <div class="bg-primary rounded shadow flex flex-col items-center p-2 w-2/5">
                        <span class="text-xs font-bold text-white">Tendência</span>
                        <span class="text-xs font-bold text-white">R$
                            {{ number_format($tendenciaAparelhosTotal, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex flex-col items-center p-2 w-1/5">
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
                    <div class="bg-orange-400 rounded shadow flex flex-col items-center p-2 w-2/5">
                        <span class="text-xs font-bold text-white">Meta</span>
                        <span class="text-xs font-bold text-white">R$
                            {{ number_format($metas[0]['meta_aparelhos'], 2, ',', '.') }}</span>
                    </div>

                </div>

            </div>

            <div class="bg-white rounded p-2 flex flex-col items-center  gap-2">
                <span class="text-lg font-bold">Acessórios Total</span>
                <span class="text-2xl font-black">R$ {{ number_format($acessoriosTotal, 2, ',', '.') }}</span>
                <div class="flex justify-between gap-2 w-full">
                    <div class="bg-primary rounded shadow flex flex-col items-center p-2 w-2/5">
                        <span class="text-xs font-bold text-white">Tendência</span>
                        <span class="text-xs font-bold text-white">R$
                            {{ number_format($tendenciaAcessorioTotal, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex flex-col items-center p-2 w-1/5">
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
                    <div class="bg-orange-400 rounded shadow flex flex-col items-center p-2 w-2/5">
                        <span class="text-xs font-bold text-white">Meta</span>
                        <span class="text-xs font-bold text-white">R$
                            {{ number_format($metas[0]['meta_acessorios'], 2, ',', '.') }}</span>
                    </div>

                </div>

            </div>
        </div>


        <div class="flex flex-col gap-2 ">
            <div class="flex flex-col items-center w-full gap-4 bg-white shadow rounded p-2">
                <span class="text-3xl font-bold italic">Total de {{ $ano }}</span>
                <div class="w-full ">
                    <x-chart wire:model="chartMetas" />
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-2">
                <div class="flex flex-col items-center w-full gap-4 bg-white shadow rounded p-2">
                    <x-chart wire:model="chartPlanosValor" />
                </div>
                <div class="flex flex-col items-center w-full gap-4 bg-white shadow rounded p-2">
                    <x-chart wire:model="chartPlanosGross" />
                </div>
            </div>
            <div class="bg-white rounded shadow w-full p-2 flex flex-col gap-4">
                <span class="text-xl font-bold italic text-center w-full">Total de Franquia {{ $ano }}</span>
                <div class="flex  flex-col lg:flex-row justify-center gap-4">
                    @foreach ($planos as $plano)
                        <div class=" w-full lg:w-1/3 bg-gray-100 rounded shadow p-2 items-center gap-4 flex flex-col ">
                            <a href="{{ route('detalhes.grupos', $plano['id']) }}"
                                class="w-full flex flex-col items-center">
                                <span class="font-bold text-lg">{{ $plano['grupo'] }}</span>
                                <div class="flex flex-row justify-between w-full gap-4">
                                    <div class="flex flex-col items-center w-full gap-2">
                                        <span>Total Plano</span>
                                        <span class="p-2 bg-white font-bold rounded shadow w-full text-xs">R$
                                            {{ number_format($plano['total'], 2, ',', '.') }}</span>
                                        <span class="p-2 bg-orange-200 font-bold rounded shadow w-full text-xs">R$
                                            {{ number_format($plano['meta_plano'], 2, ',', '.') }}</span>
                                    </div>
                                    <div class="flex flex-col items-center w-full gap-2">
                                        <span>Total Gross</span>
                                        <span
                                            class="p-2 bg-white font-bold rounded shadow w-full text-xs">{{ $plano['gross'] }}</span>
                                        <span
                                            class="p-2 bg-orange-200 font-bold rounded shadow w-full text-xs">{{ $plano['meta_gross'] }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach

                </div>

            </div>

            <div class="flex flex-col lg:flex-row gap-2">
                <div class="flex flex-col gap-2 w-full lg:w-1/2">
                    <div class="flex flex-col items-center w-full gap-4 bg-white shadow rounded p-2">
                        <span class="text-3xl font-bold italic">Ranking de Filiais</span>
                        <div class="w-full ">
                            <x-chart wire:model="chartFiliais" />
                        </div>
                    </div>

                    <div class="flex flex-col items-center w-full gap-4 bg-white shadow rounded p-2">
                        <span class="text-3xl font-bold italic">Ranking de Vendedores</span>
                        <div class="w-full ">
                            <x-chart wire:model="chartVendedores" />
                        </div>
                    </div>

                </div>

                <div class="flex flex-col items-center w-full lg:w-1/2 gap-4 bg-white shadow rounded p-2">
                    <span class="text-3xl font-bold italic">Fabricantes</span>
                    <div class="w-full ">
                        <x-chart wire:model="chartFabricante" />
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 w-full ">
                @foreach ($filiais as $filial)
                    <a href="{{ route('filial.dashboard', $filial['id']) }}">
                        <div class="rounded shadow bg-white p-2 hover:bg-secondary">
                            <div class="flex  justify-between">
                                <span class="text-sm font-bold">{{ $filial['filial'] }}</span>
                                @if ($filial['status'] === 'up')
                                    <x-icon name="o-arrow-trending-up" class="w-6 h-6  text-green-500" />
                                @endif

                                @if ($filial['status'] === 'down')
                                    <x-icon name="o-arrow-trending-down" class="w-6 h-6  text-red-500" />
                                @endif

                                @if ($filial['status'] === 'ok')
                                    <x-icon name="o-arrow-right" class="w-6 h-6  text-blue-500" />
                                @endif
                            </div>

                            <di class="flex flex-col items-center gap-2">
                                <span class="font-bold">R$
                                    {{ number_format($filial['faturamento'], 2, ',', '.') }}</span>
                                <div class="flex flex-col gap-2 lg:flex-row justify-between w-full">
                                    <span class="text-xs text-center font-bold p-2 rounded-xl bg-blue-200 shadow">R$
                                        {{ number_format($filial['tendencia'], 2, ',', '.') }}</span>
                                    <span class="text-xs text-center font-bold p-2 rounded-xl bg-orange-200 shadow">R$
                                        {{ number_format($filial['meta'], 2, ',', '.') }}</span>
                                </div>

                            </di>

                        </div>
                    </a>
                @endforeach
            </div>


        </div>



    </div>
</div>
