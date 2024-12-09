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
        Schema::create('metas_vendedores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendedor_id')->constrained('vendedores')->onDelete('cascade');
            $table->decimal('meta_faturamento', 10, 2)->default(0);
            $table->decimal('meta_acessorios', 10, 2)->default(0);
            $table->decimal('meta_aparelhos', 10, 2)->default(0);
            $table->decimal('meta_pos', 10, 2)->default(0);
            $table->integer('meta_gross_pos')->default(0);
            $table->decimal('meta_pre', 10, 2)->default(0);
            $table->integer('meta_gross_pre')->default(0);
            $table->decimal('meta_controle', 10, 2)->default(0);
            $table->integer('meta_gross_controle')->default(0);
            $table->string('mes')->nullable();
            $table->string('ano')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metas_vendedores');
    }
};
