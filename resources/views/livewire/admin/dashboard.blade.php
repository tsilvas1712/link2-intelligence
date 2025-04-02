<div>
    <x-header separator subtitle="Painel de Controle" title="Dashboard" />
    <div class="mb-8">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div class="flex flex-col items-center justify-center rounded bg-primary/50 p-4 shadow hover:bg-secondary">
                <span class="text-3xl font-bold">Total de Registros</span>
                <span class="text-5xl font-black">{{ number_format($vendas, 0, ',', '.') }}</span>
            </div>
            <div class="flex flex-col items-center justify-center rounded bg-primary/50 p-4 shadow hover:bg-secondary">
                <span class="text-3xl font-bold">Filiais</span>
                <span class="text-5xl font-black">{{ number_format($filiais, 0, ',', '.') }}</span>
            </div>
            <div class="flex flex-col items-center justify-center rounded bg-primary/50 p-4 shadow hover:bg-secondary">
                <span class="text-3xl font-bold">Vendedores</span>
                <span class="text-5xl font-black">{{ number_format($vendedores, 0, ',', '.') }}</span>
            </div>
            <div class="flex flex-col items-center justify-center rounded bg-primary/50 p-4 shadow hover:bg-secondary">
                <span class="text-3xl font-bold">Planos</span>
                <span class="text-5xl font-black">{{ number_format($planos, 0, ',', '.') }}</span>
            </div>
            <div class="flex flex-col items-center justify-center rounded bg-primary/50 p-4 shadow hover:bg-secondary">
                <span class="text-3xl font-bold">Usuários</span>
                <span class="text-5xl font-black">{{ number_format($usuarios, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
    <x-menu-separator class="" />

    <x-header separator subtitle="Painel de Controle" title="Dados Não Classificados" />
    <div>
        <x-table :headers="$this->headers()" :rows="$this->notClassificate()" with-pagination />
    </div>
</div>
