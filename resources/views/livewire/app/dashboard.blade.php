<div class="w-full" >

    <div class="w-full bg-white p-2">
        <x-tabs class="bg-base-200" wire:model="selectedTab">
            <x-tab name="charts" label="Gráficos" icon="o-chart-bar" active >
                <div class="flex flex-col  gap-2">
                    <div class="flex w-full flex-col items-center gap-4 rounded bg-white p-2 shadow">
                        <span class="text-3xl font-bold italic">Total de {{ $ano }}</span>
                        <div class="w-full">
                            <livewire:charts.apex-bars :data="$chartMetas" />
                        </div>
                    </div>
                    <div class="flex w-full flex-col lg:flex-row gap-2 ">
                        <div class="flex w-full flex-col items-center gap-4 rounded bg-white p-2 shadow">
                            <div class="flex gap-2">
                                <span class="text-3xl font-bold italic">Ranking de Filiais</span>
                            </div>
                            <x-tabs wire:model="selectedTabF">
                                <x-tab icon="s-arrow-trending-up" label="10" name="filial-up">
                                    <div class="w-full">
                                        <livewire:charts.ranking-filiais :data="$chartFiliais" />
                                    </div>
                                </x-tab>
                                <x-tab icon="s-arrow-trending-down" label="10" name="filial-down">
                                    <div class="w-full">
                                        <livewire:charts.ranking-filiais-down :data="$chartFiliaisDown" />
                                    </div>
                                </x-tab>
                            </x-tabs>
                        </div>

                        <div class="flex w-full flex-col items-center gap-4 rounded bg-white p-2 shadow">
                            <span class="text-3xl font-bold italic">Ranking de Vendedores</span>
                            <x-tabs wire:model="selectedTabV">
                                <x-tab icon="s-arrow-trending-up" label="10" name="vendedores-up">
                                    <div class="w-full">
                                        <livewire:charts.ranking-vendedores :data="$chartVendedores" />
                                    </div>
                                </x-tab>
                                <x-tab icon="s-arrow-trending-down" label="10" name="vendedores-down">
                                    <div class="w-full">
                                        <livewire:charts.ranking-vendedores-down :data="$chartVendedoresDown" />
                                    </div>
                                </x-tab>
                            </x-tabs>

                        </div>
                    </div>
                    <div>
                        <div class="flex w-full flex-col items-center gap-4 rounded lg:h-[500px] bg-white p-2 shadow">
                            <span class="text-2xl font-bold italic">Vendas</span>
                            <div class="flex w-full flex-row gap-2">
                                <div class="w-full lg:w-1/3">
                                    <livewire:charts.grupo-estoque/>
                                </div>
                                <div class="w-full lg:w-1/3">
                                    <livewire:charts.aparelhos/>
                                </div>
                                <div class="w-full lg:w-1/3">
                                    <livewire:charts.planos/>
                                </div>


                            </div>

                        </div>
                    </div>
                </div>
            </x-tab>
            <x-tab name="block" label="Totalizadores" icon="o-tv">
                @foreach($telas as $tela)
                    <div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4">
                            @foreach($tela->grupos as $grupo)
                                <div class="flex flex-col items-center gap-2 rounded bg-white p-2">
                                    <h3 class="text-lg font-bold">{{ $grupo->nome }}</h3>
                                    <span class="text-2xl font-black">R$  {{ number_format($this->getValores($grupo->id), 2, ',', '.') }}</span>
                                    <div class="flex w-full justify-between gap-2">
                                        <div class="flex w-2/5 flex-col items-center rounded bg-primary p-2 shadow">
                                            <span class="text-xs font-bold text-white">Tendência</span>
                                            <span class="text-xs font-bold text-white">R$
                                        {{ number_format($this->getValores($grupo->id), 2, ',', '.') }}</span>
                                        </div>
                                        @php
                                            $meta = random_int(50,150);
                                        @endphp
                                        <div class="flex w-1/5 flex-col items-center p-2">
                                            <span class="text-lg font-bold">{{ $meta }}%</span>
                                            @if (floatVal($meta) > 100.0)
                                                <x-icon class="h-6 w-6 text-green-500" name="o-arrow-trending-up" />
                                            @elseif (floatVal($meta) > 65.0 && floatVal($meta) < 100.0)
                                                <x-icon class="h-6 w-6 text-blue-500" name="o-arrow-right" />
                                            @else
                                                <x-icon class="h-6 w-6 text-red-500" name="o-arrow-trending-down" />
                                            @endif
                                        </div>
                                        <div class="flex w-2/5 flex-col items-center rounded bg-orange-400 p-2 shadow">
                                            <span class="text-xs font-bold text-white">Meta</span>
                                            <span class="text-xs font-bold text-white">R$
                                        {{ number_format($this->getValores($grupo->id), 2, ',', '.') }}</span>
                                        </div>

                                    </div>

                                </div>

                            @endforeach
                        </div>

                    </div>

                @endforeach
            </x-tab>
        </x-tabs>
        </div>

    </div>
</div>
