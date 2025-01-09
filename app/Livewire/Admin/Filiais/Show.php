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
    public $meta_gross_pos;
    public $meta_franquia_pos;
    public $meta_gross_controle;
    public $meta_franquia_controle;
    public $total_dias_mes;
    public $dias_trabalhado;

    public $mes;
    public $ano;

    public $meta_atual;

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
        $this->meta_atual = MetasFiliais::where('filial_id', $this->filial->id)
            ->orderBy("ano", "desc")
            ->orderBy("mes", "desc")
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
            ->orderBy("ano", "desc")
            ->orderBy("mes", "desc")
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


            /*
               "meta_faturamento" => "284444.00"
                "meta_acessorios" => "20129.00"
                "meta_aparelhos" => "284444.00"
                "mes" => "12"
                "ano" => "2024"
                "created_at" => "2025-01-09 11:34:06"
                "updated_at" => "2025-01-09 11:34:06"
                "meta_pos" => "22512.00"
                "meta_gross_pos" => 139
                "meta_pre" => "1201.00"
                "meta_gross_pre" => 10
                "meta_controle" => "12109.00"
                "meta_gross_controle" => 140
                "total_dias_mes" => "28.00"
                "dias_trabalhado" => "28.00"
            */

            $this->meta_faturamento = $this->meta->meta_faturamento;
            $this->meta_acessorios = $this->meta->meta_acessorios;
            $this->meta_aparelhos = $this->meta->meta_aparelhos;
            $this->meta_gross_pos = $this->meta->meta_gross_pos;
            $this->meta_franquia_pos = $this->meta->meta_pos;
            $this->meta_gross_controle = $this->meta->meta_gross_controle;
            $this->meta_franquia_controle = $this->meta->meta_controle;
            $this->total_dias_mes = $this->meta->total_dias_mes;
            $this->dias_trabalhado = $this->meta->dias_trabalhado;
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
            $meta->meta_gross_pos = $this->meta_gross_pos;
            $meta->meta_pos = $this->meta_franquia_pos;
            $meta->meta_gross_controle = $this->meta_gross_controle;
            $meta->meta_controle = $this->meta_franquia_controle;
            $meta->total_dias_mes = $this->total_dias_mes;
            $meta->dias_trabalhado = $this->dias_trabalhado;


            $meta->save();
        } else {
            $this->filial->meta()->create([
                'mes' => $this->mes,
                'ano' => $this->ano,
                'meta_faturamento' => floatval($this->meta_faturamento),
                'meta_acessorios' => floatval($this->meta_acessorios),
                'meta_aparelhos' => floatval($this->meta_aparelhos),
                'meta_gross_pos' => $this->meta_gross_pos,
                'meta_pos' => floatval($this->meta_franquia_pos),
                'meta_gross_controle' => $this->meta_gross_controle,
                'meta_controle' => floatval($this->meta_franquia_controle),
                'total_dias_mes' => $this->total_dias_mes,
                'dias_trabalhado' => $this->dias_trabalhado,
            ]);
        }

        $this->mount($this->filial->id);
        $this->closeDrawer();
    }
}
