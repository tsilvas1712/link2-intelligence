<div class="bg-white w-full h-full p-4 rounded">
    <x-header title="Aparelhos" icon="s-boxes" separator />
    <div>
        <x-table :headers="$this->headers()" :rows="$this->getData()" with-pagination>
            @scope('cell_total_vendas',$row)
            <div class="w-1/3 flex justify-between">
                <span class="font-bold text-right">R$</span>
                <span class="font-bold text-right">{{number_format($row->total_vendas, 2, ',', '.')}}</span>
            </div>
            @endscope
            @scope('actions',$row)
            <div class="flex gap-2">
                <x-button class="btn bg-blue-300 btn-sm" icon="s-eye" link="#" />
                <x-button class="btn bg-green-300 btn-sm" icon="fas.file-excel"  />
            </div>
            @endscope
        </x-table>
    </div>
</div>
