<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->text('area')->nullable();
            $table->text('regional')->nullable();
            $table->text('filial')->nullable();
            $table->text('gsm')->nullable();
            $table->text('gsm_portado')->nullable();
            $table->text('contrato')->nullable();
            $table->text('numero_pv')->nullable();
            $table->date('data_pedido')->nullable();
            $table->text('tipo_pedido')->nullable();
            $table->text('pedido_anterior')->nullable();
            $table->text('numero_ordem_siebel')->nullable();
            $table->text('cupom_fiscal')->nullable();
            $table->text('nota_fiscal')->nullable();
            $table->text('cod_produto')->nullable();
            $table->text('descricao_comercial')->nullable();
            $table->text('descricao')->nullable();
            $table->text('grupo_estoque')->nullable();
            $table->text('sub_grupo')->nullable();
            $table->text('familia')->nullable();
            $table->text('fabricante')->nullable();
            $table->text('categoria')->nullable();
            $table->text('tipo_produto')->nullable();
            $table->text('serial')->nullable();
            $table->integer('qtde')->nullable();
            $table->decimal('valor_tabela', 10, 2)->nullable();
            $table->decimal('valor_plano', 10, 2)->nullable();
            $table->decimal('valor_caixa')->nullable();
            $table->decimal('descontos', 10, 2)->nullable();
            $table->decimal('juros', 10, 2)->nullable();
            $table->decimal('total_item', 10, 2)->nullable();
            $table->decimal('valor_franquia', 10, 2)->nullable();
            $table->decimal('voucher_plano', 10, 2)->nullable();
            $table->text('voucher_loja')->nullable();
            $table->text('numero_voucher_loja')->nullable();
            $table->decimal('custo_inicial', 10, 2)->nullable();
            $table->decimal('impostos', 10, 2)->nullable();
            $table->decimal('desconto_compra', 10, 2)->nullable();
            $table->decimal('custo_total', 10, 2)->nullable();
            $table->text('fornecedor')->nullable();
            $table->date('dt_compra')->nullable();
            $table->text('nf_compra')->nullable();
            $table->text('financeira')->nullable();
            $table->text('forma_pgto_1')->nullable();
            $table->decimal('valor_pgto_1', 10, 2)->nullable();
            $table->text('forma_pgto_2')->nullable();
            $table->decimal('valor_pgto_2', 10, 2)->nullable();
            $table->text('forma_pgto_3')->nullable();
            $table->decimal('valor_pgto_3', 10, 2)->nullable();
            $table->text('forma_pgto_4')->nullable();
            $table->decimal('valor_pgto_4', 10, 2)->nullable();
            $table->text('forma_pgto_5')->nullable();
            $table->decimal('valor_pgto_5', 10, 2)->nullable();
            $table->text('forma_pgto_6')->nullable();
            $table->decimal('valor_pgto_6', 10, 2)->nullable();
            $table->text('forma_pgto_7')->nullable();
            $table->decimal('valor_pgto_7', 10, 2)->nullable();
            $table->text('forma_pgto_8')->nullable();
            $table->decimal('valor_pgto_8', 10, 2)->nullable();
            $table->text('forma_pgto_9')->nullable();
            $table->decimal('valor_pgto_9', 10, 2)->nullable();
            $table->text('forma_pgto_10')->nullable();
            $table->decimal('valor_pgto_10', 10, 2)->nullable();
            $table->text('cpf_vendedor')->nullable();
            $table->text('nome_vendedor')->nullable();
            $table->text('cpf_cliente')->nullable();
            $table->text('nome_cliente')->nullable();
            $table->text('uf_cliente')->nullable();
            $table->text('cidade_cliente')->nullable();
            $table->text('email_cliente')->nullable();
            $table->text('fone_cliente')->nullable();
            $table->text('plano_habilitacao')->nullable();
            $table->text('claro_sim')->nullable();
            $table->text('tipo_habilitacao')->nullable();
            $table->text('grade_precos')->nullable();
            $table->text('tipo_pacote')->nullable();
            $table->text('modalidade_venda')->nullable();
            $table->text('operadora_origem')->nullable();
            $table->decimal('desconto_plano', 10, 2)->nullable();
            $table->text('mp_do_bem')->nullable();
            $table->decimal('valor_pre', 10, 2)->nullable();
            $table->text('combo')->nullable();
            $table->text('logradouro')->nullable();
            $table->text('numero')->nullable();
            $table->text('complemento')->nullable();
            $table->text('bairro')->nullable();
            $table->text('cidade')->nullable();
            $table->text('cep')->nullable();
            $table->text('uf')->nullable();
            $table->text('tv')->nullable();
            $table->text('fixo')->nullable();
            $table->text('movel')->nullable();
            $table->text('internet')->nullable();
            $table->text('item')->nullable();
            $table->text('mpb')->nullable();
            $table->decimal('valor_plano_anterior', 10, 2)->nullable();
            $table->text('qtde_pontos')->nullable();
            $table->text('score_cliente')->nullable();
            $table->text('plano_antigo')->nullable();
            $table->text('dia_vencimento')->nullable();
            $table->text('status_tim_capture')->nullable();
            $table->decimal('base_faturamento_compra', 10, 2)->nullable();
            $table->decimal('base_faturamento_venda', 10, 2)->nullable();
            $table->text('fidelizacao')->nullable();
            $table->text('fidelizacao_plano')->nullable();
            $table->text('plug_in')->nullable();
            $table->decimal('valor_unitario', 10, 2)->nullable();
            $table->text('biometria')->nullable();
            $table->text('ean')->nullable();
            $table->text('id_tfp')->nullable();
            $table->text('status_linha')->nullable();
            //$table->primary(['tenant_id', 'numero_pv', 'data_pedido', 'tipo_pedido', 'nota_fiscal', 'cod_produto']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};
