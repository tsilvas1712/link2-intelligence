<div>
    <x-header title="{{ $vendedor->nome }}" subtitle="{{ $meses[$mes - 1]['name'] . '/' . $ano }}" separator>
        <x-slot:middle class="">
            <x-loading class="flex flex-col content-center text-primary loading-lg loading-dots lg:flex-none"
                wire:loading />
            <div class="flex gap-2 !justify-end w-full flex-col lg:flex-row " wire:loading.remove>
                <x-select icon="o-calendar" placeholder="Selecione o Mês" :options="$meses" wire:model="mesSelecionado" />
                <x-select icon="o-calendar" placeholder="Selecione o Ano" :options="$anos" wire:model="anoSelecionado" />
            </div>

        </x-slot:middle>
        <x-slot:actions class="w-full lg:w-[130px]">
            <div class="w-full">
                <x-button icon="o-funnel" class="w-full btn-primary" label="Filtrar" laze wire:click="filter"
                    wire:loading.remove />
            </div>
        </x-slot:actions>
    </x-header>

    <div class="flex flex-col gap-4">
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

                            $meta_total = $metas === null ? 0 : $metas->meta_aparelhos;

                            $meta = number_format(
                                $faturamentoTotal === 0 || $metas === null || $meta_total === 0
                                    ? 0
                                    : ($faturamentoTotal * 100) / $meta_total,
                                2,
                                ',',
                                '.',
                            );

                        @endphp
                        <span class="font-bold text-md">{{ $meta }}%</span>
                        @if (floatVal($meta) > 100.0)
                            <x-icon name="o-arrow-trending-up" class="w-6 h-6 text-green-500" />
                        @elseif (floatVal($meta) > 80.0 && floatVal($meta) < 100.0)
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
                                $aparelhosTotal === 0 || $metas === null
                                    ? 0
                                    : ($aparelhosTotal * 100) / $metas->meta_aparelhos,
                                2,
                                ',',
                                '.',
                            );
                        @endphp
                        <span class="font-bold text-md">{{ $meta }}%</span>
                        @if (floatVal($meta) > 100.0)
                            <x-icon name="o-arrow-trending-up" class="w-6 h-6 text-green-500" />
                        @elseif (floatVal($meta) > 80.0 && floatVal($meta) < 100.0)
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
                            $meta = number_format(
                                $acessoriosTotal === 0 || $metas === null
                                    ? 0
                                    : ($acessoriosTotal * 100) / $metas->meta_acessorios,
                                2,
                                ',',
                                '.',
                            );
                        @endphp
                        <span class="font-bold text-md">{{ $meta }}%</span>
                        @if (floatVal($meta) > 100.0)
                            <x-icon name="o-arrow-trending-up" class="w-6 h-6 text-green-500" />
                        @elseif (floatVal($meta) > 80.0 && floatVal($meta) < 100.0)
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
                <x-chart wire:model="chartProgressao" />
            </div>
        </div>
        <div class="flex flex-col w-full h-full gap-2 p-2 lg:flex-row">
            <div class="flex flex-col gap-4 p-2 lg:w-1/2">
                <div class="w-full bg-white rounded shadow">
                    <x-chart wire:model="chartAparelhos" />
                </div>
                <div class="w-full bg-white rounded shadow">
                    <x-chart wire:model="chartAcessorios" />
                </div>
            </div>
            <div class="flex flex-col h-full gap-4 p-2 lg:w-1/2">
                <div class="w-full bg-white rounded shadow">
                    <x-chart wire:model="chartFabricante" />
                </div>
            </div>

        </div>

        <div class="flex flex-col w-full gap-4 p-2 bg-white rounded shadow">
            <span class="w-full text-xl italic font-bold text-center">Grupos de Planos</span>
            <div class="flex flex-col justify-center gap-4 lg:flex-row">
                @foreach ($planos as $plano)
                    <div class="flex flex-col items-center w-full gap-4 p-2 bg-gray-100 rounded shadow  lg:w-1/3">
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

            <div class="flex flex-col gap-2 lg:flex-row">
                <div class="flex flex-col items-center w-full gap-4 p-2 bg-white rounded shadow">
                    <x-chart wire:model="chartPlanosValor" />
                </div>
                <div class="flex flex-col items-center w-full gap-4 p-2 bg-white rounded shadow">
                    <x-chart wire:model="chartPlanosGross" />
                </div>
            </div>

        </div>


    </div>

</div>
