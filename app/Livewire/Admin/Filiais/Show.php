<?php

namespace App\Livewire\Admin\Filiais;

use App\Models\Filial;
use App\Models\MetasFiliais;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;
    public $filial;
    public bool $showDrawer;
    public $metas;

    public $meta;

    public $meta_faturamento;
    public $meta_acessorios;
    public $meta_aparelhos;

    public $mes;
    public $ano;

    public $meses = [
        ['id' => '01', 'name' => 'Janeiro'],
        ['id' => '02', 'name' => 'Fevereiro'],
        ['id' => '03', 'name' => 'Março'],
        ['id' => '04', 'name' => 'Abril'],
        ['id' => '05', 'name' => 'Maio'],
        ['id' => '06', 'name' => 'Junho'],
        ['id' => '07', 'name' => 'Julho'],
        ['id' => '08', 'name' => 'Agosto'],
        ['id' => '09', 'name' => 'Setembro'],
        ['id' => '10', 'name' => 'Outubro'],
        ['id' => '11', 'name' => 'Novembro'],
        ['id' => '12', 'name' => 'Dezembro'],

    ];
    public function mount($id)
    {
        $this->showDrawer = false;
        $this->filial = Filial::find($id);
        $this->metas = MetasFiliais::where("filial_id", $this->filial->id)
            ->orderBy("created_at", "desc")
            ->first();
    }
    public function render()
    {
        return view('livewire.admin.filiais.show');
    }

    #[Computed]
    public function getMetas(): LengthAwarePaginator
    {
        return MetasFiliais::where('filial_id', $this->filial->id)
            ->orderBy("created_at", "desc")
            ->paginate(5);
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'mes', 'label' => 'Mês'],
            ['key' => 'ano', 'label' => 'Ano'],
            ['key' => 'meta_faturamento', 'label' => 'Faturamento'],
            ['key' => 'meta_aparelhos', 'label' => 'Aparelhos'],
            ['key' => 'meta_acessorios', 'label' => 'Acessórios'],

        ];
    }

    public function openDrawer($id = null)
    {
        if ($id) {
            $this->meta = MetasFiliais::query()
                ->where('id', $id)
                ->first();

            $this->meta_faturamento = $this->meta->meta_faturamento;
            $this->meta_acessorios = $this->meta->meta_acessorios;
            $this->meta_aparelhos = $this->meta->meta_aparelhos;
        }

        $this->showDrawer = true;
    }

    public function closeDrawer()
    {

        $this->meta = null;
        $this->meta_faturamento = null;
        $this->meta_acessorios = null;
        $this->meta_aparelhos = null;
        $this->showDrawer = false;
    }

    public function salvarMeta($id = null)
    {
        if ($id) {
            $meta = MetasFiliais::query()
                ->where('id', $id)
                ->first();
            $meta->meta_faturamento = $this->meta_faturamento;
            $meta->meta_acessorios = $this->meta_acessorios;
            $meta->meta_aparelhos = $this->meta_aparelhos;
            $meta->save();
        } else {
            $this->filial->meta()->create([
                'mes' => $this->mes,
                'ano' => $this->ano,
                'meta_faturamento' => floatval($this->meta_faturamento),
                'meta_acessorios' => floatval($this->meta_acessorios),
                'meta_aparelhos' => floatval($this->meta_aparelhos),
            ]);
        }

        $this->mount($this->filial->id);
        $this->closeDrawer();
    }
}
