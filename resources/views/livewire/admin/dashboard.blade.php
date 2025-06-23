<div>
    <x-header separator subtitle="Painel de Controle" title="Dashboard" />
    <div class="mb-8">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3 lg:grid-cols-3">
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
                <span class="text-2xl font-bold">Grupos</span>
                <span class="text-3xl font-black">{{ number_format($planos, 0, ',', '.') }}</span>
            </div>
            <div class="flex flex-col items-center justify-center rounded bg-primary/50 p-4 shadow hover:bg-secondary">
                <span class="text-2xl font-bold">Usuários</span>
                <span class="text-3xl font-black">{{ number_format($usuarios, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <x-header separator  title="Dados Não Filtrados" />
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3 lg:grid-cols-3">
        <a href="{{route('admin.not-classificate', 'grupo_estoque')}}">
        <div class="flex flex-col items-center justify-center rounded bg-primary/50 p-4 shadow hover:bg-secondary">
            <span class="text-2xl font-bold">Grupos de Estoque</span>
            <span class="text-3xl font-black">{{ number_format($grupos_estoque->count(), 0, ',', '.') }}</span>
        </div>
        </a>
        <a href="{{route('admin.not-classificate', 'modalidades_vendas')}}">
        <div class="flex flex-col items-center justify-center rounded bg-primary/50 p-4 shadow hover:bg-secondary">
            <span class="text-2xl font-bold">Modalidade de Venda</span>
            <span class="text-3xl font-black">{{ number_format($modalidades_vendas->count(), 0, ',', '.') }}</span>
        </div>
        </a>
        <a href="{{route('admin.not-classificate','planos_habilitados')}}">
        <div class="flex flex-col items-center justify-center rounded bg-primary/50 p-4 shadow hover:bg-secondary">
            <span class="text-2xl font-bold">Planos Habilitados</span>
            <span class="text-3xl font-black">{{ number_format($planos_habilitados->count(), 0, ',', '.') }}</span>
        </div>
    </div>

</div>
