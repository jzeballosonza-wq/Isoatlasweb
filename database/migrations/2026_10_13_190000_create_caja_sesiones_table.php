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
        Schema::create('caja_sesiones', function (Blueprint $table) {
            $table->id();
            $table->string('caja_nombre', 100)->default('Caja Principal');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_cierre_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('monto_inicial', 10, 2)->default(0);
            $table->dateTime('fecha_apertura');
            $table->dateTime('fecha_cierre')->nullable();
            $table->decimal('total_ventas_efectivo', 10, 2)->default(0);
            $table->decimal('total_ingresos_extra', 10, 2)->default(0);
            $table->decimal('total_egresos_gastos', 10, 2)->default(0);
            $table->decimal('monto_esperado', 10, 2)->default(0);
            $table->decimal('monto_real', 10, 2)->default(0);
            $table->decimal('diferencia', 10, 2)->default(0);
            $table->enum('estado', ['abierta', 'cerrada'])->default('abierta');
            $table->json('desglose_billetes')->nullable();
            $table->string('observaciones_apertura', 500)->nullable();
            $table->text('observaciones_cierre')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'estado']);
            $table->index('estado');
        });

        Schema::create('caja_movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_sesion_id')->constrained('caja_sesiones')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('tipo', ['ingreso', 'egreso']); // ingreso extra o gasto menor/retiro
            $table->string('concepto', 255);
            $table->decimal('monto', 10, 2);
            $table->string('comprobante', 100)->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();

            $table->index(['caja_sesion_id', 'tipo']);
        });

        if (Schema::hasTable('ventas') && !Schema::hasColumn('ventas', 'caja_sesion_id')) {
            Schema::table('ventas', function (Blueprint $table) {
                $table->foreignId('caja_sesion_id')->nullable()->after('id')->constrained('caja_sesiones')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('ventas') && Schema::hasColumn('ventas', 'caja_sesion_id')) {
            Schema::table('ventas', function (Blueprint $table) {
                $table->dropForeign(['caja_sesion_id']);
                $table->dropColumn('caja_sesion_id');
            });
        }
        Schema::dropIfExists('caja_movimientos');
        Schema::dropIfExists('caja_sesiones');
    }
};
