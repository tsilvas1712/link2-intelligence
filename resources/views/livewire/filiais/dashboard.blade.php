<div class="w-full">
    <x-header title="Detalhamento de Filiais" separator />

    <div class="flex flex-col gap-4">
        <div class="bg-white shadow rounded p-2 w-full flex gap-2">
            <x-datetime label="Data Inicial" wire:model="myDate" icon="o-calendar" />
            <x-datetime label="Data Final" wire:model="myDate" icon="o-calendar" />
            <div class="w-full">
                <x-choices label="Filiais" wire:model="multi_ids" :options="$this->getFiliais()">
                    @scope('item', $user)
                        <x-list-item :item="$user" sub-value="bio">
                            <x-slot:avatar>
                                <x-icon name="o-building-storefront" class="bg-blue-500 p-2 w-8 h8 rounded-full" />
                            </x-slot:avatar>
                        </x-list-item>
                    @endscope

                </x-choices>
            </div>







        </div>

        <div class="flex flex-col gap-2">
            <div class="bg-white rounded shadow p-2 flex flex-col items-center gap-2">
                <div class="w-full">
                    <x-chart wire:model="chartFiliais" />
                </div>
                <div>
                    <a href="#" class="btn btn-sm">Detalhar</a>
                </div>
            </div>
            <div class="flex gap-4 ">
                <div class="bg-white rounded shadow p-2 flex flex-col items-center gap-2">
                    <div class="w-full">
                        <x-chart wire:model="chartAparelhos" />
                    </div>
                    <div>
                        <a href="#" class="btn btn-sm">Detalhar</a>
                    </div>
                </div>
                <div class="bg-white rounded shadow p-2 flex flex-col items-center gap-2 w-2/3">
                    <div class="w-full">
                        <x-chart wire:model="chartAcessorios" />
                    </div>
                    <div>
                        <a href="#" class="btn btn-sm">Detalhar</a>
                    </div>
                </div>



            </div>
            <div class="bg-white rounded shadow p-2 flex flex-col items-center gap-2">
                <div class="w-full">
                    <x-chart wire:model="chartFranquias" />
                </div>
                <div>
                    <a href="#" class="btn btn-sm">Detalhar</a>
                </div>
            </div>
        </div>

    </div>
</div>
