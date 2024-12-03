<div class="w-full">
    <x-header title="Dashboard" separator>
        <x-slot:middle class="!justify-end">
            <x-button class="btn-primary btn-circle" icon="o-building-storefront" tooltip-bottom="Filiais"
                link="{{ route('filiais.dashboard') }}" />
            <x-button class="btn-primary btn-circle" icon="o-users" tooltip-bottom="Vendedores"
                link="{{ route('vendedores.dashboard') }}" />
            <x-button class="btn-primary btn-circle" icon="o-cursor-arrow-ripple" tooltip-bottom="Planos" />

        </x-slot:middle>
        <x-slot:actions>
            <x-button class="btn bg-red-500 btn-circle" icon="o-arrow-right-end-on-rectangle" tooltip-bottom="Sair"
                link="{{ route('logout') }}" />
        </x-slot:actions>
    </x-header>

    <div class="flex flex-col w-full gap-4 ">

        <div class="grid grid-cols-3 w-full rounded shadow gap-2">
            <div class="bg-white rounded p-2 flex flex-col items-center  gap-2">
                <span class="text-lg font-bold">Faturamento Total</span>
                <span class="text-2xl font-black">R$ {{ number_format($faturamentoTotal, 2, ',', '.') }}</span>
                <div class="flex justify-between gap-2 w-full">
                    <div class="bg-primary rounded shadow flex flex-col items-center p-2 w-1/3">
                        <span class="text-xs font-bold text-white">Tendência</span>
                        <span class="text-xs font-bold text-white">R$
                            {{ number_format($tendenciaFaturamento, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex flex-col items-center p-2 w-1/3">
                        <span class="text-xl font-bold">99%</span>
                        <x-icon name="o-arrow-trending-up" class="w-6 h-6 text-green-500" />
                    </div>
                    <div class="bg-orange-400 rounded shadow flex flex-col items-center p-2 w-1/3">
                        <span class="text-xs font-bold text-white">Meta</span>
                        <span class="text-xs font-bold text-white">3.000.000,00</span>
                    </div>

                </div>

            </div>

            <div class="bg-white rounded p-2 flex flex-col items-center gap-2">
                <span class="text-lg font-bold">Franquia Total</span>
                <span class="text-2xl font-black">R$ {{ number_format($franquiaTotal, 2, ',', '.') }}</span>
                <div class="flex justify-between gap-2 w-full">
                    <div class="bg-primary rounded shadow flex flex-col items-center p-2 w-1/3">
                        <span class="text-xs font-bold text-white">Tendência</span>
                        <span class="text-xs font-bold text-white">R$
                            {{ number_format($tendenciaFranquiaTotal, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex flex-col items-center p-2 w-1/3">
                        <span class="text-xl font-bold">80%</span>
                        <x-icon name="o-arrow-right" class="w-6 h-6 text-blue-500" />
                    </div>
                    <div class="bg-orange-400 rounded shadow flex flex-col items-center p-2 w-1/3">
                        <span class="text-xs font-bold text-white">Meta</span>
                        <span class="text-xs font-bold text-white">3.000.000,00</span>
                    </div>

                </div>

            </div>

            <div class="bg-white rounded p-2 flex flex-col items-center  gap-2">
                <span class="text-lg font-bold">Acessórios Total</span>
                <span class="text-2xl font-black">R$ {{ number_format($acessoriosTotal, 2, ',', '.') }}</span>
                <div class="flex justify-between gap-2 w-full">
                    <div class="bg-primary rounded shadow flex flex-col items-center p-2 w-1/3">
                        <span class="text-xs font-bold text-white">Tendência</span>
                        <span class="text-xs font-bold text-white">R$
                            {{ number_format($tendenciaAcessorioTotal, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex flex-col items-center p-2 w-1/3">
                        <span class="text-xl font-bold">75%</span>
                        <x-icon name="o-arrow-trending-down" class="w-6 h-6 text-red-500" />
                    </div>
                    <div class="bg-orange-400 rounded shadow flex flex-col items-center p-2 w-1/3">
                        <span class="text-xs font-bold text-white">Meta</span>
                        <span class="text-xs font-bold text-white">3.000.000,00</span>
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

            <div class="flex gap-2">
                <div class="flex flex-col gap-2 w-1/2">
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

                <div class="flex flex-col items-center w-1/2 gap-4 bg-white shadow rounded p-2">
                    <span class="text-3xl font-bold italic">Fabricantes</span>
                    <div class="w-full ">
                        <x-chart wire:model="chartFabricante" />
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <div class="grid grid-cols-3 gap-2 w-1/2 ">
                @foreach ($filiais as $filial)
                    <div class="rounded shadow bg-white p-2 hover:bg-secondary">
                        <div class="flex  justify-between">
                            <span class="text-xs font-bold">{{ $filial['filial'] }}</span>
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

                    </div>
                @endforeach
            </div>
            <div class="grid grid-cols-3 gap-2 p-2  w-1/2 bg-gray-500 rounded shadow">
                @foreach ($vendedores as $vendedor)
                    <div class="rounded shadow bg-white p-2">
                        <div class="flex justify-between">
                            <span class="text-xs font-bold">{{ $vendedor['nome'] }} </span>
                            @if ($vendedor['status'] === 'up')
                                <x-icon name="o-arrow-trending-up" class="w-6 h-6  text-green-500" />
                            @endif

                            @if ($vendedor['status'] === 'down')
                                <x-icon name="o-arrow-trending-down" class="w-6 h-6 text-red-500 " />
                            @endif

                            @if ($vendedor['status'] === 'ok')
                                <x-icon name="o-arrow-right" class="w-6 h-6 text-blue-500 " />
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

        </div>



    </div>
</div>
