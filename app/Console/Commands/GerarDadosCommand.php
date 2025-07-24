<?php

namespace App\Console\Commands;

use App\Models\Datasys;
use App\Models\Filial;
use App\Models\Import;
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
        $filiais = Datasys::query()
            ->select('Filial')
            ->groupBy('Filial')
            ->orderBy('Filial')
            ->get();
        $filiaisI = Import::query()
            ->select('filial')
            ->groupBy('filial')
            ->orderBy('filial')
            ->get();


        foreach ($filiais as $filial) {
            if ($filial->Filial == '') {
                continue;
            }

            $ifExists = Filial::query()->where('filial', $this->formatFilial($filial->Filial))->first();
            if (!$ifExists) {
                $filialM = new Filial();
                $filialM->filial = $this->formatFilial($filial->Filial);
                $filialM->save();
            }
        }

        foreach ($filiaisI as $filialI) {
            if ($filialI->filial == '') {
                continue;
            }

            $ifExists = Filial::query()->where('filial', $filialI->filial)->first();
            if (!$ifExists) {
                $filialM = new Filial();
                $filialM->filial = $filialI->filial;
                $filialM->save();
            }
        }
    }

    public function criarVendedor()
    {
        $vendedoresD = Datasys::query()
            ->select('CPF_x0020_Vendedor', 'Nome_x0020_Vendedor')
            ->groupBy('CPF_x0020_Vendedor')
            ->groupBy('Nome_x0020_Vendedor')
            ->get();

        $vendedores = Import::query()
            ->select('cpf_vendedor', 'nome_vendedor')
            ->groupBy('cpf_vendedor')
            ->groupBy('nome_vendedor')
            ->get();

        foreach ($vendedoresD as $vendedor) {
            if ($vendedor->Nome_x0020_Vendedor == '') {
                continue;
            }

            $ifExists = Vendedor::query()->where('cpf', trim(str_replace("'", '', $vendedor->CPF_x0020_Vendedor)))->first();
            if (!$ifExists) {
                $vendedorM = new Vendedor();
                $vendedorM->cpf = trim(str_replace("'", '', $vendedor->CPF_x0020_Vendedor));
                $vendedorM->nome = trim($vendedor->Nome_x0020_Vendedor);
                $vendedorM->save();
            }
        }

        foreach ($vendedores as $vendedor) {
            if ($vendedor->nome_vendedor == '') {
                continue;
            }

            $ifExists = Vendedor::query()->where('cpf', trim(str_replace("'", '', $vendedor->cpf_vendedor)))->first();
            if (!$ifExists) {
                $vendedorM = new Vendedor();
                $vendedorM->cpf = trim(str_replace("'", '', $vendedor->cpf_vendedor));
                $vendedorM->nome = trim($vendedor->nome_vendedor);
                $vendedorM->save();
            }
        }
    }

    public function formatFilial($filial_name)
    {
        $numFilial = array_slice(explode(" - ", strtolower($filial_name)), 0, 1);
        $nomeFilial = array_slice(explode(" - ", strtolower($filial_name)), 1, 1);

        $full = $numFilial[0] . '-' . str_replace(" ", "", ucwords($nomeFilial[0]));

        return $full;
    }
}