<?php

namespace App\Livewire\Admin;

use App\Models\Plano;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Csv\Reader;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Planos extends Component
{

    use WithFileUploads, WithPagination;
    public $file;
    public $search;

    public $plano;

    public $plano_habilitado;
    public $valor_franquia;

    public $modal = false;
    public function render()
    {

        return view('livewire.admin.planos');
    }

    public function openModal($id = null)
    {
        if ($id) {
            $this->plano = Plano::find($id);

            $this->plano_habilitado = $this->plano->plano_habilitado;
            $this->valor_franquia = $this->plano->valor;
        }
        $this->modal = true;
    }

    public function closeModal()
    {
        $this->plano = null;
        $this->plano_habilitado = null;
        $this->valor_franquia = null;
        $this->modal = false;
    }

    public function savePlano()
    {

        $this->validate([
            'plano_habilitado' => 'required',
            'valor_franquia' => 'required | decimal'
        ]);

        if ($this->plano) {
            $this->plano->update([
                'valor' => floatval($this->valor_franquia)
            ]);
        } else {
            Plano::create([
                'plano_habilitado' => strtoupper($this->plano_habilitado),
                'valor' => floatval($this->valor_franquia)
            ]);
        }

        $this->closeModal();
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

            $isUpdated = $this->updatePlano($record['plano_habilitado'], $record['valor_franquia']);

            if (!$isUpdated) {
                Plano::create([
                    'plano_habilitado' => strtoupper($record['plano_habilitado']),
                    'valor' => $record['valor_franquia']
                ]);
            }
        }


        $this->file = null;
    }

    public function updatePlano($plano_habilitado, $valor)
    {
        $plano = Plano::query()
            ->where('plano_habilitado', operator: strtoupper($plano_habilitado))
            ->first();

        if (!$plano) {
            return false;
        }

        $plano->update([
            'valor' => floatval($valor)
        ]);

        return true;
    }
    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'plano_habilitado', 'label' => 'Plano Habilitado'],
            ['key' => 'valor', 'label' => 'Valor Franquia']
        ];
    }
    #[Computed]
    public function getPlanos(): LengthAwarePaginator
    {
        return Plano::query()
            ->when($this->search, function ($query) {
                return $query->where('plano_habilitado', 'like', '%' . strtoupper($this->search) . '%');
            })
            ->orderBy('plano_habilitado', 'asc')
            ->paginate(10);
    }
}
