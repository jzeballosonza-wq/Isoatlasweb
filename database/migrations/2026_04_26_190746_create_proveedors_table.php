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
    Schema::create('proveedors', function (Blueprint $table) {
        $table->id();

        $table->string('nombre'); // empresa
        $table->string('direccion');
        $table->string('telefono');

        $table->string('email')->nullable();
        $table->string('contacto')->nullable(); // nombre proveedor
        $table->string('celular')->nullable();

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedors');
    }
};
