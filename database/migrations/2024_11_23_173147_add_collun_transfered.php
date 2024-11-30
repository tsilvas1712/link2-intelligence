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
        Schema::table('datasys', function (Blueprint $table) {
            $table->boolean('transfered')->default(false);
        });

        Schema::table('imports', function (Blueprint $table) {
            $table->boolean('transfered')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datasys', function (Blueprint $table) {
            //
        });
    }
};
