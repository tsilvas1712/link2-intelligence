<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meta_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupos');
            $table->foreignId('filial_id')->nullable()->constrained('filials');
            $table->foreignId('vendedor_id')->nullable()->constrained('vendedores');
            $table->string('mes', 20);
            $table->string('ano', 4);
            $table->decimal('valor_meta', 15, 2)->default(0.00);
            $table->integer('quantidade')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meta_groups');
    }
};
