<?php

namespace App\Livewire\Admin\Vendedores;

use App\Models\MetasVendedores;
use App\Models\Vendedor;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;
    public $vendedor;
    public bool $showDrawer;
    public $metas;

    public $meta;

    public $meta_faturamento;
    public $meta_acessorios;
    public $meta_aparelhos;
    public $meta_gross_pos;

    public $meta_gross_controle;

    public $meta_franquia_controle;

    public $meta_franquia_pos;

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
        $this->vendedor = Vendedor::find($id);
        $this->metas = MetasVendedores::where("vendedor_id", $this->vendedor->id)
            ->orderBy("created_at", "desc")
            ->first();

        $this->meta_atual = MetasVendedores::where('vendedor_id', $this->vendedor->id)
            ->orderBy("ano", "desc")
            ->orderBy("mes", "desc")
            ->first();
    }
    public function render()
    {
        return view('livewire.admin.vendedores.show');
    }

    #[Computed]
    public function getMetas(): LengthAwarePaginator
    {
        return MetasVendedores::where('vendedor_id', $this->vendedor->id)
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
            $this->meta = MetasVendedores::query()
                ->where('id', $id)
                ->first();

            //meta_pos" => "28915.00"
            //"meta_gross_pos" => 388
            // "meta_pre" => "10244.00"
            //"meta_gross_pre" => 2490
            //"meta_controle" => "15665.00"
            //"meta_gross_controle" => 951

            $this->meta_faturamento = $this->meta->meta_faturamento;
            $this->meta_acessorios = $this->meta->meta_acessorios;
            $this->meta_aparelhos = $this->meta->meta_aparelhos;
            $this->meta_franquia_controle = $this->meta->meta_controle;
            $this->meta_franquia_pos = $this->meta->meta_pos;
            $this->meta_gross_controle = $this->meta->meta_gross_controle;
            $this->meta_gross_pos = $this->meta->meta_gross_pos;
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
            $meta = MetasVendedores::query()
                ->where('id', $id)
                ->first();
            $meta->meta_faturamento = $this->meta_faturamento;
            $meta->meta_acessorios = $this->meta_acessorios;
            $meta->meta_aparelhos = $this->meta_aparelhos;
            $meta->meta_controle = $this->meta_franquia_controle;
            $meta->meta_pos = $this->meta_franquia_pos;
            $meta->meta_gross_controle = $this->meta_gross_controle;
            $meta->meta_gross_pos = $this->meta_gross_pos;

            dd($meta);
            $meta->save();
        } else {
            $this->vendedor->metas()->create([
                'mes' => $this->mes,
                'ano' => $this->ano,
                'meta_faturamento' => floatval($this->meta_faturamento),
                'meta_acessorios' => floatval($this->meta_acessorios),
                'meta_aparelhos' => floatval($this->meta_aparelhos),
                'meta_controle' => floatval($this->meta_franquia_controle),
                'meta_pos' => floatval($this->meta_franquia_pos),
                'meta_gross_controle' => floatval($this->meta_gross_controle),
                'meta_gross_pos' => floatval($this->meta_gross_pos),
            ]);
        }

        $this->mount($this->vendedor->id);
        $this->closeDrawer();
    }
}
