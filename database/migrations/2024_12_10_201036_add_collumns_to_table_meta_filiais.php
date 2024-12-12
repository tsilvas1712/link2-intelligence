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
        Schema::table('metas_filiais', function (Blueprint $table) {
            $table->decimal('total_dias_mes', 10, 2)->default(0.00)->after('filial_id');
            $table->decimal('dias_trabalhado', 10, 2)->default(0.00)->after('total_dias_mes');
            $table->enum('tipo_filial', ['shopping', 'rua'])->nullable()->after('dias_trabalhado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('metas_filiais', function (Blueprint $table) {
            //
        });
    }
};
