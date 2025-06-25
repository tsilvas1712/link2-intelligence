<div class="w-full flex flex-col gap-4 p-4 bg-white rounded">
    <x-header title="{{$grupo_estoque}}" separator />
    <div>
        <x-table :headers="$this->headers()" :rows="$this->getData()" with-pagination>
            @scope('cell_data_pedido',$row)
                {{ Carbon\Carbon::parse($row->data_pedido)->format('d/m/Y')  }}
            @endscope
            @scope('cell_filial_id',$row)
                {{$row->filial->filial}}
            @endscope
            @scope('cell_vendedor_id',$row)
                {{$row->vendedor->nome}}
            @endscope
            @scope('cell_valor_caixa',$row)
                R$ {{ number_format($row->valor_caixa, 2, ',', '.') }}
            @endscope

        </x-table>
    </div>
</div>
