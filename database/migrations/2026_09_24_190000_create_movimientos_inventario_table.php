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
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('tipo', 50); // compra, venta, ajuste_positivo, ajuste_negativo, anulacion_venta, anulacion_compra, inicial
            $table->integer('cantidad');
            $table->integer('stock_anterior');
            $table->integer('stock_nuevo');
            $table->decimal('costo_unitario', 10, 2)->nullable();
            $table->decimal('precio_unitario', 10, 2)->nullable();
            $table->string('motivo')->nullable();
            $table->string('referencia_tipo')->nullable(); // App\Models\Venta, App\Models\Compra, App\Models\AjusteInventario
            $table->unsignedBigInteger('referencia_id')->nullable();
            $table->timestamps();

            $table->index(['producto_id', 'created_at']);
            $table->index('tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};
