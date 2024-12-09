<div>
    <x-header :title="$filial->filial" separator />

    <div class="flex flex-col w-full gap-4">
        <div class="rounded shadow w-full bg-white p-2 flex justify-between items-center">
            <div class="flex w-2/3 gap-2 ">
                <div class="w-1/3">
                    <x-select label="Selecione o Mês" placeholder="Escolha um mês" icon="o-calendar" :options="$meses"
                        wire:model="selectedMonth" />
                </div>


            </div>

            <x-button label="Filtrar" class="btn-primary" wire:click="filter" />

        </div>

        <div class="w-full bg-white overflow-scroll p-2 rounded shadow">

            <x-chart wire:model="chartVendas" />

        </div>
    </div>
</div>
