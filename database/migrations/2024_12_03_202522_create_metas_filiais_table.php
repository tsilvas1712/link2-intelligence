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
        Schema::create('metas_filiais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filial_id')->constrained('filials')->onDelete('cascade');
            $table->decimal('meta_faturamento', 10, 2)->default(0);
            $table->decimal('meta_acessorios', 10, 2)->default(0);
            $table->decimal('meta_aparelhos', 10, 2)->default(0);
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
        Schema::dropIfExists('metas_filiais');
    }
};
