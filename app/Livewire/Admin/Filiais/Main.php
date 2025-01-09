<?php

namespace App\Livewire\Admin\Filiais;

use App\Models\Filial;
use App\Models\MetasFiliais;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Csv\Reader;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
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
        return view('livewire.admin.filiais.main');
    }

    #[Computed]
    public function getFiliais(): LengthAwarePaginator
    {
        return Filial::query()
            ->when($this->search, function ($query) {
                return $query->where('filial', 'like', '%' . strtoupper($this->search) . '%');
            })
            ->orderBy('filial', 'asc')
            ->paginate(10);
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'filial', 'label' => 'Filial']
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

            $filial = $this->getFilial($record['filial_id']);

            try {
                MetasFiliais::create([
                    'filial_id' => $filial,
                    'meta_faturamento' => $record['meta_faturamento'],
                    'meta_acessorios' => $record['meta_acessorios'],
                    'meta_aparelhos' => $record['meta_aparelhos'],
                    'meta_pos' => $record['meta_pos'],
                    'meta_gross_pos' => $record['meta_gross_pos'],
                    'meta_pre' => $record['meta_pre'],
                    'meta_gross_pre' => $record['meta_gross_pre'],
                    'meta_controle' => $record['meta_controle'],
                    'meta_gross_controle' => $record['meta_gross_controle'],
                    'mes' => $record['mes'],
                    'ano' => $record['ano'],
                    'total_dias_mes' => $record['total_dias_mes'],
                    'dias_trabalhado' => $record['dias_trabalhado'],

                ]);
            } catch (\Exception $e) {
                $this->addError('file', 'Erro ao importar arquivo' . $e->getMessage());
            }
        }
        $this->file = null;
    }

    public function getFilial($filial_name)
    {
        $numFilial = array_slice(explode(" - ", strtolower($filial_name)), 0, 1);
        $nomeFilial = array_slice(explode(" - ", strtolower($filial_name)), 1, 1);

        $full = $numFilial[0] . '-' . str_replace(" ", "", ucwords($nomeFilial[0]));

        $getFilial = Filial::query()
            ->select('id')
            ->where('filial', $full)
            ->first();

        if (!$getFilial) {
            $getFilial = Filial::create([
                'filial' => $full
            ]);
            return $getFilial->id;
        }

        return $getFilial->id;
    }
}
