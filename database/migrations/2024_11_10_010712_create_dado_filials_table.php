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
        Schema::create('dados_filiais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filial_id')->constrained('filials');
            $table->integer('total_dias');
            $table->decimal('meta_total', 10, 2);
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
        Schema::dropIfExists('dado_filials');
    }
};
