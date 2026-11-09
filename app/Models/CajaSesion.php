<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CajaSesion extends Model
{
    use HasFactory;

    protected $table = 'caja_sesiones';

    protected $fillable = [
        'caja_nombre',
        'user_id',
        'user_cierre_id',
        'monto_inicial',
        'fecha_apertura',
        'fecha_cierre',
        'total_ventas_efectivo',
        'total_ingresos_extra',
        'total_egresos_gastos',
        'monto_esperado',
        'monto_real',
        'diferencia',
        'estado',
        'desglose_billetes',
        'observaciones_apertura',
        'observaciones_cierre'
    ];

    protected $casts = [
        'monto_inicial' => 'decimal:2',
        'total_ventas_efectivo' => 'decimal:2',
        'total_ingresos_extra' => 'decimal:2',
        'total_egresos_gastos' => 'decimal:2',
        'monto_esperado' => 'decimal:2',
        'monto_real' => 'decimal:2',
        'diferencia' => 'decimal:2',
        'desglose_billetes' => 'array',
        'fecha_apertura' => 'datetime',
        'fecha_cierre' => 'datetime',
    ];

    // 🔗 Cajero que abrió la caja
    public function usuarioApertura()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 🔗 Cajero o supervisor que cerró la caja
    public function usuarioCierre()
    {
        return $this->belongsTo(User::class, 'user_cierre_id');
    }

    // 🔗 Movimientos manuales de caja (ingresos extras o retiros de dinero)
    public function movimientos()
    {
        return $this->hasMany(CajaMovimiento::class, 'caja_sesion_id')->latest('id');
    }

    // 🔗 Ventas asociadas a este turno de caja
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'caja_sesion_id')->latest('id');
    }

    /**
     * Comprueba si la caja está actualmente abierta
     */
    public function getEstaAbiertaAttribute(): bool
    {
        return $this->estado === 'abierta';
    }

    /**
     * Calcula en tiempo real el saldo esperado en caja
     */
    public function calcularMontoEsperadoEnVivo(): float
    {
        // 1. Total ventas registradas en el turno
        $ventasTotal = $this->ventas()->sum('total');
        if ($ventasTotal == 0 && $this->esta_abierta) {
            // Si las ventas no fueron marcadas con el id de caja pero ocurrieron entre la apertura y ahora
            $ventasTotal = Venta::where('created_at', '>=', $this->fecha_apertura)
                ->where(function($q) {
                    $q->whereNull('caja_sesion_id')->orWhere('caja_sesion_id', $this->id);
                })
                ->sum('total');
        }

        // 2. Ingresos extras y gastos
        $ingresosExtra = $this->movimientos()->where('tipo', 'ingreso')->sum('monto');
        $egresosGastos = $this->movimientos()->where('tipo', 'egreso')->sum('monto');

        return (float) ($this->monto_inicial + $ventasTotal + $ingresosExtra - $egresosGastos);
    }

    /**
     * Badge visual del estado de arqueo
     */
    public function getResultadoArqueoAttribute(): array
    {
        if ($this->estado === 'abierta') {
            return [
                'clave' => 'abierta',
                'label' => 'En Curso (Turno Abierto)',
                'badge' => 'bg-success-subtle text-success border border-success-subtle',
                'icon'  => 'fas fa-door-open'
            ];
        }

        $dif = (float) $this->diferencia;

        if (abs($dif) < 0.01) {
            return [
                'clave' => 'cuadrada',
                'label' => 'Caja Cuadrada',
                'badge' => 'bg-success-subtle text-success border border-success-subtle',
                'icon'  => 'fas fa-check-double'
            ];
        } elseif ($dif > 0) {
            return [
                'clave' => 'sobrante',
                'label' => 'Sobrante (+Bs. ' . number_format($dif, 2) . ')',
                'badge' => 'bg-info-subtle text-info border border-info-subtle',
                'icon'  => 'fas fa-plus-circle'
            ];
        } else {
            return [
                'clave' => 'faltante',
                'label' => 'Faltante (-Bs. ' . number_format(abs($dif), 2) . ')',
                'badge' => 'bg-danger-subtle text-danger border border-danger-subtle',
                'icon'  => 'fas fa-exclamation-triangle'
            ];
        }
    }
}
