<div class="w-full flex flex-col">
    <x-header title="Filiais" subtitle="Detalhamento" separator />

    <div class="flex flex-col gap-4">
        <div class="rounded shadow w-full bg-white p-2 flex justify-between items-center">
            <div class="flex w-2/3 gap-2 ">
                <div class="w-1/3">
                    <x-select label="Selecione o Mês" placeholder="Escolha um mês" icon="o-calendar" :options="$meses"
                        wire:model="selectedMonth" />
                </div>
                <div class="w-full">
                    <x-choices label="Filiais" wire:model="filiais_id" :options="$this->getFiliais()" class="w-1/2">
                        @scope('item', $filial)
                            <x-list-item :item="$filial" sub-value="bio">
                                <x-slot:avatar>
                                    <x-icon name="o-user" class="bg-orange-100 p-2 w-8 h8 rounded-full" />
                                </x-slot:avatar>
                            </x-list-item>
                        @endscope
                    </x-choices>
                </div>

            </div>

            <x-button label="Filtrar" class="btn-primary" wire:click="filter" />

        </div>

        <div class="flex flex-col gap-4">

            <div class="bg-white rounded shadow p-2">
                <x-chart wire:model="chartFiliaisVendas" />
            </div>

            <div class="flex gap-2 w-full">
                <div class="bg-white rounded shadow p-2 w-full">
                    <x-chart wire:model="chartAparelhos" />
                </div>
                <div class="bg-white rounded shadow p-2 w-full">
                    <x-chart wire:model="chartAcessorios" />
                </div>
                <div class="bg-white rounded shadow p-2 w-full">
                    <x-chart wire:model="chartFranquia" />
                </div>

            </div>

            <div class="grid grid-cols-4 bg-white shadow rounded gap-2 p-2">
                @foreach ($filiais as $filial)
                    <a href="{{ route('filiais.show', $filial->id) }}">
                        <div class="bg-gray-200 hover:bg-secondary p-2  rounded shadow">
                            {{ $filial->filial }}
                        </div>
                    </a>
                @endforeach

            </div>




        </div>

    </div>





</div>
