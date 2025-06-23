<div>
<x-header separator subtitle="Dados de Sincronização com Datasys" title="Datasys" />

    <div>
        <x-tabs wire:model="selectedTab">
            <x-tab name="error-tab" label="Erros Datasys" icon="s-shield-exclamation" class="bg-red-50">
                <x-table :headers="$this->headersErrors()" :rows="$this->errorMongo()" with-pagination>
                    @scope('cell_filial', $mongo)
                    {{ $mongo->data['Filial'] }}
                    @endscope
                    @scope('cell_data', $mongo)
                    {{ $mongo->data['Numero_x0020_Pedido'] }}
                    @endscope
                    @scope('cell_pedido', $mongo)
                    {{ \Carbon\Carbon::parse($mongo->data['Data_x0020_pedido'])->format('d/m/Y') }}
                    @endscope
                    @scope('cell_grupo_estoque', $mongo)
                    {{ $mongo->data['Grupo_x0020_Estoque'] }}
                    @endscope
                    @scope('cell_migrated', $mongo)
                    <div class="flex items-center justify-center">
                        <x-icon name="s-check-circle" class="text-green-900 h-6" />
                    </div>
                    @endscope
                    @scope('actions', $mongo)
                    <x-button icon="o-eye" spinner class="btn-sm bg-green-900 text-white" />
                    @endscope
                </x-table>
            </x-tab>
            <x-tab name="sync-tab" label="Dados Sincronizado" icon="s-circle-stack" class="bg-green-50">
                <x-table :headers="$this->headersMongo()" :rows="$this->dataMongo()" with-pagination>
                    @scope('cell_filial', $mongo)
                    {{ $mongo->data['Filial'] }}
                    @endscope
                    @scope('cell_data', $mongo)
                    {{ $mongo->data['Numero_x0020_Pedido'] }}
                    @endscope
                    @scope('cell_pedido', $mongo)
                    {{ \Carbon\Carbon::parse($mongo->data['Data_x0020_pedido'])->format('d/m/Y') }}
                    @endscope
                    @scope('cell_grupo_estoque', $mongo)
                    {{ $mongo->data['Grupo_x0020_Estoque'] }}
                    @endscope
                    @scope('cell_migrated', $mongo)
                    <div class="flex items-center justify-center">
                        <x-icon name="s-check-circle" class="text-green-900 h-6" />
                    </div>
                    @endscope
                    @scope('actions', $mongo)
                    <x-button icon="o-eye" spinner class="btn-sm bg-green-900 text-white" />
                    @endscope
                </x-table>
            </x-tab>
        </x-tabs>

    </div>
</div>
