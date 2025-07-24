<?php

namespace App\Imports;

use App\Jobs\ImportExcelJob;
use App\Models\Filial;
use App\Models\SyncMongo;
use App\Models\VendaAtual;
use App\Models\Vendedor;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\ImportFailed;

class SyncMongoImport implements ToModel, WithHeadingRow,WithColumnFormatting, WithChunkReading,ShouldQueue, WithEvents
{
    //use Queueable,Importable;

  /**
   * @param array $row
   *
   * @return \Illuminate\Database\Eloquent\Model|null
   */
   public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,

        ];
    }
  public function model(array $row)
  {


    $data_pedido = Date::excelToDateTimeObject($row['data_pedido']);

    $data = [
      "area" => $row["area"],
      "regional" => $row["regional"],
      "filial_id" => $this->getFilialId($row["filial"]),
      "vendedor_id" => $this->getVendedorId($row["cpf_vendedor"], $row["nome_vendedor"]),
      "gsm" => $row["gsm"],
      "gsm_portado" => $row["gsm_portado"],
      "contrato" => $row["contrato"],
      "numero_pv" => $row["numero_pv"],
      "data_pedido" => $data_pedido->format('Y-m-d'),
      "tipo_pedido" => $row["tipo_pedido"],
      "nota_fiscal" => $row["nota_fiscal"],
      "cod_produto" => $row["cod_produto"],
      "modalidade_venda" => $row["modalidade_venda"],
      "descricao_comercial" => $row["descricao_comercial"],
      "descricao" => $row["descricao"],
      "grupo_estoque" => $row["grupo_estoque"],
      "sub_grupo" => $row["sub_grupo"],
      "familia" => $row["familia"],
      "fabricante" => $row["fabricante"],
      "categoria" => $row["categoria"],
      "tipo_produto" => $row["tipo_produto"],
      "serial" => $row["serial"],
      "qtde" => $row["qtde"],
      "valor_tabela" => $row["valor_tabela"],
      "valor_plano" => $row["valor_plano"],
      "valor_caixa" => $row["valor_caixa"],
      "descontos" => $row["descontos"],
      "juros" => $row["juros"],
      "total_item" => $row["total_item"],
      "valor_franquia" => $row["valor_franquia"],
      "desconto_compra" => $row["desconto_compra"],
      "custo_total" => $row["custo_total"],
      "cpf_cliente" => $row["cpf_cliente"],
      "nome_cliente" => $row["nome_cliente"],
      "uf_cliente" => $row["uf_cliente"],
      "cidade_cliente" => $row["cidade_cliente"],
      "fone_cliente" => $row["fone_cliente"],
      "plano_habilitacao" => $row["plano_habilitacao"],
      "valor_pre" => $row["valor_pre"],
      "combo" => $row["combo"],
      "valor_plano_anterior" => $row["valor_plano_anterior"],
      "qtde_pontos" => $row["qtde_pontos"],
      "base_faturamento_compra" => $row["base_faturamento_compra"],
      "base_faturamento_venda" => $row["base_faturamento_venda"],
      "valor_unitario" => $row["valor_unitario"],
      "biometria" => $row["biometria"],
      "status_linha" => $row["status_linha"],
    ];


    //return ImportExcelJob::dispatch($data);
    return new VendaAtual($data);
  }

  public function getFilialId($filial)
  {

    $filialModel = Filial::query()->where('filial', $this->formatFilial($filial))->first();

    if (!$filialModel) {
      $filialModel = new Filial();
      $filialModel->filial = $this->formatFilial($filial);
      $filialModel->save();
    }

    return $filialModel->id;
  }

  public function getVendedorId($cpf, $nome)
  {
    $vendedor = Vendedor::query()
      ->where('cpf', trim(str_replace("'", "", $cpf)))
      ->first();

    if (!$vendedor) {
      $vendedor = new Vendedor();
      $vendedor->cpf = trim(str_replace("'", "", $cpf));
      $vendedor->nome = trim($nome);
      $vendedor->save();
    }

    return $vendedor->id;
  }

  public function formatFilial($filial_name)
  {
    $numFilial = array_slice(explode(" - ", strtolower($filial_name)), 0, 1);
    $nomeFilial = array_slice(explode(" - ", strtolower($filial_name)), 1, 1);

    $full = $numFilial[0] . '-' . str_replace(" ", "", ucwords($nomeFilial[0]));

    return $full;
  }

  public function chunkSize(): int
    {
        return 500;
    }

    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function(ImportFailed $event) {
               Log::error('Import failed', ['error' => $event->getException()->getMessage()]);
            },
        ];
    }
}
