<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {

            $table->id();

            $table->string('codigo')->unique();

            // 🔥 CLAVE FORÁNEA CORREGIDA (IMPORTANTE)
            $table->unsignedBigInteger('categoria_id');

            $table->string('nombre');
            $table->text('descripcion')->nullable();

            $table->string('imagen')->nullable();

            $table->integer('stock')->default(0);
            $table->integer('stock_minimo')->default(0);
            $table->integer('stock_maximo')->default(0);

            $table->decimal('precio_compra', 10, 2);
            $table->decimal('precio_venta', 10, 2);

            $table->date('fecha_ingreso')->nullable();

            $table->timestamps();

            // 🔥 FOREIGN KEY (SEPARADO → MÁS SEGURO)
            $table->foreign('categoria_id')
                  ->references('id')
                  ->on('categorias')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};