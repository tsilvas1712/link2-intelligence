<div class="w-full flex flex-col gap-4">
    @if (!$isLoading && !$isProcessing)
        <div class="w-full bg-white p-2">
            <x-form wire:submit="uploadFile" class="w-full flex justify-between">
                <x-file wire:model="file" label="Upload para Atualização" hint="Somente  XLSX" />

                <x-button label="Enviar Arquivo" class="btn-primary" type="submit" spinner="uploadFile" />

            </x-form>
        </div>
    @endif

    @if ($isLoading)
        <div class="w-full bg-white p-2">
            <h3 class="text-2xl text-center text-primary font-black">Seu arquivo está sendo processado, em uma base de
                segurança</h3>
            <div class="flex justify-center w-full p-8">
                <x-loading class="loading-bars loading-lg text-primary" />
            </div>

        </div>
    @endif

    @if ($isProcessing)
        <div class="w-full flex flex-col gap-8 items-center bg-white p-2">
            <h3 class="text-2xl text-center text-primary font-black">Os Dados estão sendo Processados em sua base de
                dados</h3>

            <div class="flex justify-center items-center  p-2 gap-4 bg-primary rounded-lg w-1/3">
                <span class="font-bold text-white">Por Favor aguarde um momento</span>
                <x-loading class="loading-dots loading-lg text-white" />
            </div>

        </div>
    @endif
    <div class="bg-white w-full flex p-2">
        <div class="flex w-full gap-2  ">
            <x-datetime label="Data Inicial" wire:model="dt_start" />
            <x-datetime label="Data Final" wire:model="dt_end" />
        </div>

        <div class="w-full flex gap-2 ">
            <x-choices class="w-full" label="Selecione as Filiais" wire:model.live="selectedFiliais" :options="$filiais"
                option-label="filial" height="w-full" icon="o-users" />
            <x-choices class="w-full" label="Selecione os Vendedores" wire:model.live="selectedVendedores"
                :options="$this->getVendedores()" option-label="nome" height="w-full" icon="o-users" />
        </div>

        <div class="flex flex-col justify-end items-center w-1/3">
            <x-button wire:click="updateDash" label="Atualizar" class="btn-primary" spinner="updateDash" />
        </div>

    </div>

    <div class="w-full bg-white p-2 flex flex-col gap-4">


        <x-tabs class="bg-base-200" wire:model="selectedTab">
            <x-tab name="charts" label="Gráficos" icon="o-chart-bar" active>
                @foreach ($categories as $category)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4 p-2">
                        @foreach ($category->grupos->where('principal', true) as $grupo)
                            @php
                                $data = [];
                                $data['grupo_id'] = $grupo->id;
                                $data['dt_start'] = $dt_start;
                                $data['dt_end'] = $dt_end;
                                $data['filiais'] = $selectedFiliais;
                                $data['vendedores'] = $selectedVendedores;
                            @endphp
                            <a href="{{ route('detalhamento', $data) }}">
                                <livewire:app.components.group-chart :group_id="$grupo->id" :wire:key="$grupo->id" />
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </x-tab>
            <x-tab name="block" label="Totalizadores" icon="o-tv">
                @foreach ($categories as $category)
                    <div class="bg-white p-2 rounded-sm ">
                        <div class="flex flex-col w-full gap-2">
                            <h2 class="text-sm font-bold text-primary">.:: {{ $category->name }} ::.</h2>
                            <hr />
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4 p-2">
                            @foreach ($category->grupos->where('principal', true) as $grupo)
                                <a href="{{ route('detalhamento', $data) }}">
                                    <livewire:app.components.group-total :group_id="$grupo->id" :wire:key="$grupo->nome" />
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </x-tab>
        </x-tabs>
    </div>

    <div class="w-full bg-white p-2 flex flex-col gap-4">
        <livewire:app.components.chart-pie-ranking-filiais :filiais="$filiais" :dt_inicio="$dt_start" :dt_fim="$dt_end" />

    </div>
    <div class="w-full bg-white p-2 flex flex-col gap-4">
        <livewire:app.components.chart-pie-ranking-vendedores :vendedores="$vendedores" :dt_inicio="$dt_start" :dt_fim="$dt_end" />
    </div>



</div>
