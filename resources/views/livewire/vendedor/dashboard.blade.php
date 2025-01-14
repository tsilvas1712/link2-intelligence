<div>
    <x-header title="{{ $vendedor->nome }}" subtitle="{{ $meses[$mes - 1]['name'] . '/' . $ano }}" separator>
        <x-slot:middle class="">
            <x-loading class="text-primary loading-lg loading-dots flex flex-col lg:flex-none content-center"
                wire:loading />
            <div class="flex gap-2 !justify-end w-full flex-col lg:flex-row " wire:loading.remove>
                <x-select icon="o-calendar" placeholder="Selecione o Mês" :options="$meses" wire:model="mesSelecionado" />
                <x-select icon="o-calendar" placeholder="Selecione o Ano" :options="$anos" wire:model="anoSelecionado" />
            </div>

        </x-slot:middle>
        <x-slot:actions class="w-full lg:w-[130px]">
            <div class="w-full">
                <x-button icon="o-funnel" class="btn-primary w-full" label="Filtrar" laze wire:click="filter"
                    wire:loading.remove />
            </div>
        </x-slot:actions>
    </x-header>

    <div class="flex flex-col gap-4">
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

                            $meta_total = $metas === null ? 0 : $metas->meta_acessorios + $metas->meta_aparelhos;

                            $meta = number_format(
                                $faturamentoTotal === 0 || $metas === null || $meta_total === 0
                                    ? 0
                                    : ($faturamentoTotal * 100) / $meta_total,
                                2,
                                ',',
                                '.',
                            );

                        @endphp
                        <span class="text-md font-bold">{{ $meta }}%</span>
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
                            {{ number_format($metas === null ? 0 : $metas->meta_acessorios + $metas->meta_aparelhos, 2, ',', '.') }}</span>
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
                                $aparelhosTotal === 0 || $metas === null
                                    ? 0
                                    : ($aparelhosTotal * 100) / $metas->meta_aparelhos,
                                2,
                                ',',
                                '.',
                            );
                        @endphp
                        <span class="text-md font-bold">{{ $meta }}%</span>
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
                            {{ number_format($metas === null ? 0 : $metas->meta_aparelhos, 2, ',', '.') }}</span>
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
                                $acessoriosTotal === 0 || $metas === null
                                    ? 0
                                    : ($acessoriosTotal * 100) / $metas->meta_acessorios,
                                2,
                                ',',
                                '.',
                            );
                        @endphp
                        <span class="text-md font-bold">{{ $meta }}%</span>
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
                            {{ number_format($metas === null ? 0 : $metas->meta_acessorios, 2, ',', '.') }}</span>
                    </div>

                </div>

            </div>
        </div>
        <div class="bg-white rounded shadow p-4 flex flex-col gap-2">
            <x-chart wire:model="chartVendasDiarias" />
            <x-chart wire:model="chartDiario" />
        </div>
        <div class="flex flex-col items-center w-full gap-4 bg-white shadow rounded p-2">
            <span class="text-3xl font-bold italic">Progressão Mensal</span>
            <div class="w-full ">
                <x-chart wire:model="chartProgressao" />
            </div>
        </div>
        <div class="flex flex-col lg:flex-row gap-2 w-full h-full p-2">
            <div class="flex flex-col gap-4 lg:w-1/2 p-2">
                <div class="bg-white rounded shadow w-full">
                    <x-chart wire:model="chartAparelhos" />
                </div>
                <div class="bg-white rounded shadow w-full">
                    <x-chart wire:model="chartAcessorios" />
                </div>
            </div>
            <div class="flex flex-col gap-4 lg:w-1/2 h-full p-2">
                <div class="bg-white rounded shadow  w-full">
                    <x-chart wire:model="chartFabricante" />
                </div>
            </div>

        </div>

        <div class="bg-white rounded shadow w-full p-2 flex flex-col gap-4">
            <span class="text-xl font-bold italic text-center w-full">Grupos de Planos</span>
            <div class="flex flex-col lg:flex-row justify-center gap-4">
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

            <div class="flex flex-col lg:flex-row gap-2">
                <div class="flex flex-col items-center w-full gap-4 bg-white shadow rounded p-2">
                    <x-chart wire:model="chartPlanosValor" />
                </div>
                <div class="flex flex-col items-center w-full gap-4 bg-white shadow rounded p-2">
                    <x-chart wire:model="chartPlanosGross" />
                </div>
            </div>

        </div>


    </div>

</div>
