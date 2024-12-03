<div>
    <x-header :title="$filial->filial" separator />
    <div class="bg-white p-2 rounded shadow">
        <div class="bg-gray-100 w-full  p-2 rounded shadow">
            <div class="flex  gap-2 w-full">
                <x-datetime label="Data Inicial" wire:model="myDate" icon="o-calendar" />

                <x-datetime label="Data Final" wire:model="myDate" icon="o-calendar" />

                <div class="w-full">
                    <x-choices label="Multiple" wire:model="vendedor_multi_ids" :options="$vendedores" />

                </div>

            </div>



        </div>
        <x-table class="mt-4" :headers="$this->headers()" :rows="$this->getData()" with-pagination>
            @scope('cell_vendedor_id', $venda)
                <span class="text-xs">{{ $venda->vendedor->nome }}</span>
            @endscope
            @scope('cell_valor_caixa', $venda)
                <span>R$ {{ number_format($venda->valor_caixa, 2, ',', '.') }}</span>
            @endscope
            @scope('cell_descricao', $venda)
                <span class="text-xs">{{ $venda->descricao }}</span>
            @endscope
            @scope('cell_data_pedido', $venda)
                <span>{{ date('d/m/Y', strtotime($venda->data_pedido)) }}</span>
            @endscope


        </x-table>


    </div>
</div>
