<div>
    <x-header separator subtitle="Listagem" title="Dados Não Classificados" />

    <x-table :headers="$headers" :rows="$data_table" >
        @scope('cell_created_at', $tabela)
            {{ $tabela->created_at->format('d/m/Y') }}
        @endscope
    </x-table>


</div>
