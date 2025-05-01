<div>
    <x-header separator subtitle="Painel de Controle" title="Dashboard" />
    <div class="mb-8">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3 lg:grid-cols-5">
            <div class="flex flex-col items-center justify-center rounded bg-primary/50 p-4 shadow hover:bg-secondary">
                <span class="text-2xl font-bold">Total de Registros</span>
                <span class="text-3xl font-black">{{ number_format($vendas, 0, ',', '.') }}</span>
            </div>
            <div class="flex flex-col items-center justify-center rounded bg-primary/50 p-4 shadow hover:bg-secondary">
                <span class="text-2xl font-bold">Filiais</span>
                <span class="text-3xl font-black">{{ number_format($filiais, 0, ',', '.') }}</span>
            </div>
            <div class="flex flex-col items-center justify-center rounded bg-primary/50 p-4 shadow hover:bg-secondary">
                <span class="text-2xl font-bold">Vendedores</span>
                <span class="text-3xl font-black">{{ number_format($vendedores, 0, ',', '.') }}</span>
            </div>
            <div class="flex flex-col items-center justify-center rounded bg-primary/50 p-4 shadow hover:bg-secondary">
                <span class="text-2xl font-bold">Planos</span>
                <span class="text-3xl font-black">{{ number_format($planos, 0, ',', '.') }}</span>
            </div>
            <div class="flex flex-col items-center justify-center rounded bg-primary/50 p-4 shadow hover:bg-secondary">
                <span class="text-2xl font-bold">Usuários</span>
                <span class="text-3xl font-black">{{ number_format($usuarios, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
    <x-menu-separator class="" />

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
            <x-tab name="unfiltred-tab" label="Dados Não Filtrados" icon="s-table-cells" class="bg-blue-50">
                <x-table :headers="$this->headers()" :rows="$this->notClassificate()" with-pagination />
            </x-tab>
        </x-tabs>

    </div>
</div>
