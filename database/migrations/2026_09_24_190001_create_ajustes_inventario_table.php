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
        Schema::create('ajustes_inventario', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50)->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->date('fecha');
            $table->string('tipo', 50); // incremento, decremento, conteo_fisico, mixto
            $table->string('motivo_general'); // Conteo físico, Daño/Merma, Muestra vitrina, Corrección de entrada
            $table->text('observaciones')->nullable();
            $table->integer('total_items')->default(0);
            $table->timestamps();
        });

        Schema::create('detalle_ajustes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ajuste_id')->constrained('ajustes_inventario')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->integer('stock_anterior');
            $table->integer('cantidad'); // unidades que se agregan o quitan
            $table->integer('stock_nuevo');
            $table->string('tipo_movimiento', 20); // entrada, salida
            $table->decimal('costo_unitario', 10, 2)->nullable();
            $table->string('motivo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_ajustes');
        Schema::dropIfExists('ajustes_inventario');
    }
};
