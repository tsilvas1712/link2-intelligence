<?php

namespace App\Console\Commands;

use App\Models\Datasys;
use App\Models\Filial;
use App\Models\Vendedor;
use Illuminate\Console\Command;

class GerarDadosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datasys:gerar-dados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gerar Dados de Filiais e Vendedores';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->criarFilial();
        $this->criarVendedor();
    }

    public function criarFilial()
    {
        $filiais = Datasys::query()->select('Filial')->groupBy('Filial')->get();

        foreach ($filiais as $filial) {
            if ($filial->filial == '') {
                continue;
            }

            $ifExists = Filial::query()->where('filial', $filial->Filial)->first();
            if (!$ifExists) {
                $filialM = new Filial();
                $filialM->filial = $filial->Filial;
                $filialM->save();
            }
        }
    }

    public function criarVendedor()
    {
        $vendedores = Datasys::query()
            ->select('CPF_x0020_Vendedor', 'Nome_x0020_Vendedor')
            ->groupBy('CPF_x0020_Vendedor')
            ->groupBy('Nome_x0020_Vendedor')
            ->get();

        foreach ($vendedores as $vendedor) {

            $ifExists = Vendedor::query()->where('cpf', $vendedor->CPF_x0020_Vendedor)->first();
            if (!$ifExists) {
                $vendedorM = new Vendedor();
                $vendedorM->cpf = $vendedor->CPF_x0020_Vendedor;
                $vendedorM->nome = $vendedor->Nome_x0020_Vendedor;
                $vendedorM->save();
            }
        }
    }
}
