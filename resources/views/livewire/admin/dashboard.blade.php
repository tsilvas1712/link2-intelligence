<div>
    <x-header title="Dashboard" subtitle="Painel de Controle" separator />
    <div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div class="bg-primary/50 rounded shadow p-4 flex flex-col justify-center items-center hover:bg-secondary">
                <span class="text-3xl font-bold">Total de Registros</span>
                <span class="text-5xl font-black">{{ number_format($vendas, 0, ',', '.') }}</span>
            </div>
            <div class="bg-primary/50 rounded shadow p-4 flex flex-col justify-center items-center hover:bg-secondary">
                <span class="text-3xl font-bold">Filiais</span>
                <span class="text-5xl font-black">{{ number_format($filiais, 0, ',', '.') }}</span>
            </div>
            <div class="bg-primary/50 rounded shadow p-4 flex flex-col justify-center items-center hover:bg-secondary">
                <span class="text-3xl font-bold">Vendedores</span>
                <span class="text-5xl font-black">{{ number_format($vendedores, 0, ',', '.') }}</span>
            </div>
            <div class="bg-primary/50 rounded shadow p-4 flex flex-col justify-center items-center hover:bg-secondary">
                <span class="text-3xl font-bold">Planos</span>
                <span class="text-5xl font-black">{{ number_format($planos, 0, ',', '.') }}</span>
            </div>
            <div class="bg-primary/50 rounded shadow p-4 flex flex-col justify-center items-center hover:bg-secondary">
                <span class="text-3xl font-bold">Usuários</span>
                <span class="text-5xl font-black">{{ number_format($usuarios, 0, ',', '.') }}</span>
            </div>
        </div>


    </div>
</div>
