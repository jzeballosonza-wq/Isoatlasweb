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
        Schema::table('productos', function (Blueprint $table) {
            $table->foreignId('almacen_id')
                ->nullable()
                ->after('categoria_id')
                ->constrained('almacenes')
                ->nullOnDelete();

            $table->string('ubicacion_estante', 100)
                ->nullable()
                ->after('stock_maximo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['almacen_id']);
            $table->dropColumn(['almacen_id', 'ubicacion_estante']);
        });
    }
};
