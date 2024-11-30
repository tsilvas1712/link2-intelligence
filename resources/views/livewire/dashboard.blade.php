<div class="w-full flex flex-col  ">
    <x-header title="Dashboard" separator />

    <div class="flex flex-col w-full gap-4">
        <div class="flex gap-4 w-full p-4 bg-slate-400 rounded">
            <x-datetime label="Data Inicial" wire:model="dtInicial" icon="o-calendar" />
            <x-datetime label="Data Final" wire:model="dtFinal" icon="o-calendar" />
            <x-select label="Filial" icon="o-user" :options="$this->getFiliais()" wire:model="selectedFilial" />
            <x-select label="Vendedor" icon="o-user" :options="$this->getVendedores()" wire:model="selectedFilial" />
            <x-button icon="o-funnel" wire:click="{{ $this->filtrar() }}" />


        </div>
        <div class="flex flex-col gap-4 ">
            <div class="bg-white flex flex-col gap-4 p-2 shadow rounded items-center w-full">

                <div class="p-4 w-full">
                    <x-chart wire:model="chartTotal" />
                </div>
                <div>
                    <a href="#" class="btn btn-sm">Detalhar</a>
                </div>
            </div>

            <div class="flex w-full gap-2  ">
                <div class="bg-white w-full flex flex-col items-center p-2 shadow rounded">
                    <div class="w-full">
                        <x-chart wire:model="chartFiliais" />
                    </div>
                    <div>
                        <a href="{{ route('filiais.dashboard') }}" class="btn btn-sm">Detalhar</a>
                    </div>
                </div>

                <div class="bg-white w-full flex flex-col items-center p-2 shadow rounded justify-between">
                    <div class="w-full">
                        <x-chart wire:model="chartVendedores" />
                    </div>
                    <div>
                        <a href="{{ route('vendedores.dashboard') }}" class="btn btn-sm">Detalhar</a>
                    </div>
                </div>
            </div>

            <div class="w-full flex flex-col gap-4 bg-white rounded shadow p-2 items-center">
                <div class="w-full">
                    <x-chart wire:model="chartEvolucao" />
                </div>
                <div>
                    <a href="#" class="btn btn-sm">Detalhar</a>
                </div>
            </div>

        </div>


    </div>

</div>
