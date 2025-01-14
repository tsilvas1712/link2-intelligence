<?php

namespace App\Livewire\Admin\Vendedores;

use App\Models\MetasVendedores;
use App\Models\Vendedor;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Csv\Reader;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Main extends Component
{
    use WithPagination, WithFileUploads;

    public $search;
    public $file;
    public function render()
    {
        return view('livewire.admin.vendedores.main');
    }

    #[Computed]
    public function getVendedores(): LengthAwarePaginator
    {
        return Vendedor::query()
            ->when($this->search, function ($query) {
                return $query->where('nome', 'like', '%' . strtoupper($this->search) . '%');
            })
            ->orderBy('nome', 'asc')
            ->paginate(10);
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'nome', 'label' => 'Vendedor'],
            ['key' => 'cpf', 'label' => 'CPF']
        ];
    }

    public function import()
    {

        $this->validate([
            'file' => 'required|file|mimes:csv',
            'file.mines' => 'Arquivo deve ser do tipo CSV'
        ], [
            'file.required' => 'Selecione um arquivo',
            'file.mimes' => 'Arquivo deve ser do tipo CSV'
        ]);

        $file = $this->file->path();
        $csvFile = Reader::createFromPath($file, 'r');
        $csvFile->setDelimiter(';');

        $csvFile->setHeaderOffset(0);

        foreach ($csvFile as $record) {

            $vendedor_id = $this->getVendedor($record['cpf_nome']);

            try {
                MetasVendedores::create([
                    'vendedor_id' => $vendedor_id,
                    'meta_acessorios' => $record['meta_acessorios'],
                    'meta_aparelhos' => $record['meta_aparelhos'],
                    'meta_pos' => $record['meta_pos_franquia'],
                    'meta_gross_pos' => $record['meta_pos_gross'],
                    'meta_pre' => $record['meta_franquia_total'],
                    'meta_controle' => $record['meta_controle_franquia'],
                    'meta_gross_controle' => $record['meta_controle_gross'],
                    'mes' => $record['mes'],
                    'ano' => $record['ano'],


                ]);
            } catch (\Exception $e) {
                $this->addError('file', 'Erro ao importar arquivo' . $record['cpf_nome'] . $e->getMessage());
            }
        }
        $this->file = null;
    }

    public function getVendedor($cpf_vendedor)
    {
        $cpf = str_replace("'", "", $cpf_vendedor);

        $getVendedor = Vendedor::query()
            ->select('id')
            ->where('cpf', $cpf)
            ->first();


        return $getVendedor->id;
    }
}
