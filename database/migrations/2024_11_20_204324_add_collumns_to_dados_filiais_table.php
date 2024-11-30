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
        Schema::table('dados_filiais', function (Blueprint $table) {
            $table->integer('quant_aparelhos')->nullable();
            $table->decimal('valor_aparelhos', 10, 2)->nullable();
            $table->integer('quant_acessorios')->nullable();
            $table->decimal('valor_acessorios', 10, 2)->nullable();
            $table->integer('quant_chip')->nullable();
            $table->decimal('valor_chip', 10, 2)->nullable();
            $table->decimal('valor_recarga', 10, 2)->nullable();
            $table->integer('gross_pos')->nullable();
            $table->integer('gross_pre')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dados_filiais', function (Blueprint $table) {
            //
        });
    }
};
