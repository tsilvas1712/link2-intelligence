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
        Schema::create('dados_vendedores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendedor_id')->constrained('vendedores');
            $table->foreignId('filial_id')->constrained('filials');
            $table->integer('quant_aparelhos');
            $table->decimal('valor_aparelhos', 10, 2);
            $table->integer('quant_acessorios');
            $table->decimal('valor_acessorios', 10, 2);
            $table->integer('quant_chip');
            $table->decimal('valor_chip', 10, 2);
            $table->decimal('valor_recarga', 10, 2);
            $table->string('mes');
            $table->string('ano');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dados_vendedors');
    }
};
