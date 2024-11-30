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
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            $table->text('area')->nullable();
            $table->text('regional')->nullable();
            $table->foreignId('filial_id')->constrained('filials');
            $table->foreignId('vendedor_id')->constrained('vendedores');
            $table->text('filial')->nullable();
            $table->text('gsm')->nullable();
            $table->text('gsm_portado')->nullable();
            $table->text('contrato')->nullable();
            $table->text('numero_pv')->nullable();
            $table->date('data_pedido')->nullable();
            $table->text('tipo_pedido')->nullable();
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
            $table->decimal('desconto_compra', 10, 2)->nullable();
            $table->decimal('custo_total', 10, 2)->nullable();
            $table->text('cpf_cliente')->nullable();
            $table->text('nome_cliente')->nullable();
            $table->text('uf_cliente')->nullable();
            $table->text('cidade_cliente')->nullable();
            $table->text('fone_cliente')->nullable();
            $table->text('plano_habilitacao')->nullable();
            $table->decimal('valor_pre', 10, 2)->nullable();
            $table->text('combo')->nullable();
            $table->decimal('valor_plano_anterior', 10, 2)->nullable();
            $table->text('qtde_pontos')->nullable();
            $table->decimal('base_faturamento_compra', 10, 2)->nullable();
            $table->decimal('base_faturamento_venda', 10, 2)->nullable();
            $table->decimal('valor_unitario', 10, 2)->nullable();
            $table->text('biometria')->nullable();
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
        Schema::dropIfExists('vendas');
    }
};
