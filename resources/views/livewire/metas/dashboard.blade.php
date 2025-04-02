<div class="w-full">
    <x-header separator subtitle="{{ $meses[$mes - 1]['name'] . '/' . $ano }}" title="Dashboard">
        <x-slot:middle>
            <div class="flex w-full flex-col items-center gap-2 lg:flex-row">
                <x-loading class="loading-dots loading-lg text-primary" wire:loading />
                <div class="w-full" wire:loading.remove>
                    <x-choices :options="$this->getFiliais()" label="Filiais" wire:model="filiais_id">
                        @scope('item', $filial)
                            <x-list-item :item="$filial" sub-value="bio">
                                <x-slot:avatar>
                                    <x-icon class="h8 w-8 rounded-full bg-orange-100 p-2" name="o-home" />
                                </x-slot:avatar>
                            </x-list-item>
                        @endscope
                    </x-choices>
                </div>
                <div class="flex w-full flex-row items-center gap-2" wire:loading.remove>
                    <x-select :options="$meses" class="w-2/4" icon="o-calendar" label="Mês"
                        placeholder="Selecione o Mês" wire:model="mesSelecionado" />
                    <x-select :options="$anos" class="w-1/4" icon="o-calendar" label="Ano"
                        placeholder="Selecione o Ano" wire:model="anoSelecionado" />
                </div>
            </div>

        </x-slot:middle>
        <x-slot:actions>
            <x-button class="btn-primary" icon="o-funnel" label="Filtrar" spinner wire:click="filter" />
        </x-slot:actions>

    </x-header>

    @if ($metas === null)
        <div class="flex h-96 w-full flex-col items-center justify-center">
            <x-icon class="h-24 w-24 text-gray-400" name="o-arrow-right" />
            <span class="text-lg font-bold text-gray-400">Nenhuma meta cadastrada para o mês e ano selecionado</span>
        </div>
    @endif

    <div wire:init="init">
        @if ($metas)
            <div class="flex w-full flex-col gap-4">
                <div class="grid w-full grid-cols-1 gap-2 rounded shadow lg:grid-cols-3">
                    <div class="flex flex-col items-center gap-2 rounded bg-white p-2">
                        <span class="text-lg font-bold">Faturamento Total</span>
                        <span class="text-2xl font-black">R$ {{ number_format($faturamentoTotal, 2, ',', '.') }}</span>
                        <div class="flex w-full justify-between gap-2">
                            <div class="flex w-2/5 flex-col items-center rounded bg-primary p-2 shadow">
                                <span class="text-xs font-bold text-white">Tendência</span>
                                <span class="text-xs font-bold text-white">R$
                                    {{ number_format($tendenciaFaturamento, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex w-1/5 flex-col items-center p-2">
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
                                    {{ number_format($metas[0]['meta_faturamento'], 2, ',', '.') }}</span>
                            </div>

                        </div>

                    </div>

                    <div class="flex flex-col items-center gap-2 rounded bg-white p-2">
                        <span class="text-lg font-bold">Aparelhos Total</span>
                        <span class="text-2xl font-black">R$ {{ number_format($aparelhosTotal, 2, ',', '.') }}</span>
                        <div class="flex w-full justify-between gap-2">
                            <div class="flex w-2/5 flex-col items-center rounded bg-primary p-2 shadow">
                                <span class="text-xs font-bold text-white">Tendência</span>
                                <span class="text-xs font-bold text-white">R$
                                    {{ number_format($tendenciaAparelhosTotal, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex w-1/5 flex-col items-center p-2">
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
                                    {{ number_format($metas[0]['meta_aparelhos'], 2, ',', '.') }}</span>
                            </div>

                        </div>

                    </div>

                    <div class="flex flex-col items-center gap-2 rounded bg-white p-2">
                        <span class="text-lg font-bold">Acessórios Total</span>
                        <span class="text-2xl font-black">R$ {{ number_format($acessoriosTotal, 2, ',', '.') }}</span>
                        <div class="flex w-full justify-between gap-2">
                            <div class="flex w-2/5 flex-col items-center rounded bg-primary p-2 shadow">
                                <span class="text-xs font-bold text-white">Tendência</span>
                                <span class="text-xs font-bold text-white">R$
                                    {{ number_format($tendenciaAcessorioTotal, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex w-1/5 flex-col items-center p-2">
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
                                    {{ number_format($metas[0]['meta_acessorios'], 2, ',', '.') }}</span>
                            </div>

                        </div>

                    </div>
                </div>



                <!-- CHARTS RANKING E FABRICANTES -->
                <div class="flex flex-col gap-2">
                    <div class="flex w-full flex-col items-center gap-4 rounded bg-white p-2 shadow">
                        <span class="text-3xl font-bold italic">Total de {{ $ano }}</span>
                        <div class="w-full">
                            <livewire:charts.apex-bars :data="$chartMetas" />
                        </div>
                    </div>
                    <div class="flex h-full flex-col gap-2 lg:flex-row">
                        <div class="flex w-full flex-col gap-2 lg:w-1/2">
                            <div class="flex w-full flex-col items-center gap-4 rounded bg-white p-2 shadow">
                                <div class="flex gap-2">
                                    <span class="text-3xl font-bold italic">Ranking de Filiais</span>

                                </div>
                                <x-tabs wire:model="selectedTab">
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
                        <div class="flex max-h-screen w-full flex-col items-center justify-center lg:w-1/2">
                            <livewire:charts.apex-pie :data="$chartFabricante" />
                        </div>

                    </div>

                </div>

                <div class="flex w-full flex-col gap-4 rounded bg-white p-2 shadow">
                    <span class="w-full text-center text-xl font-bold italic">Produtos
                        {{ $ano }}</span>
                    <div class="flex flex-col justify-center gap-4 lg:flex-row">
                        @if ($planos)
                            @foreach ($planos as $plano)
                                <div
                                    class="flex w-full flex-col items-center gap-4 rounded bg-gray-100 p-2 shadow lg:w-1/3">
                                    <a class="flex w-full flex-col items-center"
                                        href="{{ route('detalhes.grupos', $plano['id']) }}">
                                        <span class="text-lg font-bold">{{ $plano['grupo'] }}</span>
                                        <div class="flex w-full flex-row justify-between gap-4">
                                            <div class="flex w-full flex-col items-center gap-2">
                                                <span>Total Plano</span>
                                                <span class="w-full rounded bg-white p-2 text-xs font-bold shadow">R$
                                                    {{ number_format($plano['total'], 2, ',', '.') }}</span>
                                                <span
                                                    class="w-full rounded bg-orange-200 p-2 text-xs font-bold shadow">R$
                                                    {{ number_format($plano['meta_plano'], 2, ',', '.') }}</span>
                                            </div>
                                            <div class="flex w-full flex-col items-center gap-2">
                                                <span>Total Gross</span>
                                                <span
                                                    class="w-full rounded bg-white p-2 text-xs font-bold shadow">{{ $plano['gross'] }}</span>
                                                <span
                                                    class="w-full rounded bg-orange-200 p-2 text-xs font-bold shadow">{{ $plano['meta_gross'] }}</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @endif

                    </div>

                    <div class="flex w-full flex-col gap-4 lg:flex-row">
                        <div class="flex w-full flex-col items-center lg:w-1/2">
                            <span class="text-center text-xl font-bold italic">Vendas</span>
                            <livewire:charts.produtos :data="$chartPlanosValor" />
                        </div>
                        <div class="flex w-full flex-col items-center lg:w-1/2">
                            <span class="text-center text-xl font-bold italic">Gross</span>
                            <livewire:charts.produtos-gross :data="$chartPlanosGross" />
                        </div>

                    </div>

                </div>


                <!-- FILIAIS -->
                <div class="flex gap-4">
                    <div class="grid w-full grid-cols-2 gap-2 lg:grid-cols-4">
                        @if ($filiais)
                            @foreach ($filiais as $filial)
                                <a href="{{ route('filial.dashboard', $filial['id']) }}">
                                    <div class="rounded bg-white p-2 shadow hover:bg-secondary">
                                        <div class="flex justify-between">
                                            <span class="text-sm font-bold">{{ $filial['filial'] }}</span>
                                            @if ($filial['status'] === 'up')
                                                <x-icon class="h-6 w-6 text-green-500" name="o-arrow-trending-up" />
                                            @endif

                                            @if ($filial['status'] === 'down')
                                                <x-icon class="h-6 w-6 text-red-500" name="o-arrow-trending-down" />
                                            @endif

                                            @if ($filial['status'] === 'ok')
                                                <x-icon class="h-6 w-6 text-blue-500" name="o-arrow-right" />
                                            @endif
                                        </div>

                                        <di class="flex flex-col items-center gap-2">
                                            <span class="font-bold">R$
                                                {{ number_format($filial['faturamento'], 2, ',', '.') }}</span>
                                            <div class="flex w-full flex-col justify-between gap-2 lg:flex-row">
                                                <span
                                                    class="rounded-xl bg-blue-200 p-2 text-center text-xs font-bold shadow">R$
                                                    {{ number_format($filial['tendencia'], 2, ',', '.') }}</span>
                                                <span
                                                    class="rounded-xl bg-orange-200 p-2 text-center text-xs font-bold shadow">R$
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
