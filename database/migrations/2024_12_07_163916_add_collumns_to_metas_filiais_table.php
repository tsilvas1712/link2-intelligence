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
            $table->decimal('meta_pos', 10, 2)->default(0);
            $table->integer('meta_gross_pos')->default(0);
            $table->decimal('meta_pre', 10, 2)->default(0);
            $table->integer('meta_gross_pre')->default(0);
            $table->decimal('meta_controle', 10, 2)->default(0);
            $table->integer('meta_gross_controle')->default(0);
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
