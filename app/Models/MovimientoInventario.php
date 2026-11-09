<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    use HasFactory;

    protected $table = 'movimientos_inventario';

    protected $fillable = [
        'producto_id',
        'user_id',
        'tipo',
        'cantidad',
        'stock_anterior',
        'stock_nuevo',
        'costo_unitario',
        'precio_unitario',
        'motivo',
        'referencia_tipo',
        'referencia_id'
    ];

    protected $casts = [
        'costo_unitario' => 'decimal:2',
        'precio_unitario' => 'decimal:2',
        'cantidad' => 'integer',
        'stock_anterior' => 'integer',
        'stock_nuevo' => 'integer',
    ];

    // 🔗 Relación con Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // 🔗 Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Propiedades visuales y badges según el tipo de movimiento
     */
    public function getTipoInfoAttribute(): array
    {
        return match ($this->tipo) {
            'compra' => [
                'label' => 'Entrada por Compra',
                'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'badge_bs' => 'bg-success-subtle text-success border border-success-subtle',
                'icon' => 'fas fa-truck-loading',
                'color' => '#10b981',
                'signo' => '+',
                'clase_signo' => 'text-success'
            ],
            'venta' => [
                'label' => 'Salida por Venta',
                'badge' => 'bg-blue-50 text-blue-700 border-blue-200',
                'badge_bs' => 'bg-primary-subtle text-primary border border-primary-subtle',
                'icon' => 'fas fa-cash-register',
                'color' => '#3b82f6',
                'signo' => '-',
                'clase_signo' => 'text-danger'
            ],
            'ajuste_positivo' => [
                'label' => 'Ajuste Ingreso (+)',
                'badge' => 'bg-purple-50 text-purple-700 border-purple-200',
                'badge_bs' => 'bg-info-subtle text-info-emphasis border border-info-subtle',
                'icon' => 'fas fa-plus-circle',
                'color' => '#8b5cf6',
                'signo' => '+',
                'clase_signo' => 'text-success'
            ],
            'ajuste_negativo' => [
                'label' => 'Ajuste Merma / Salida (-)',
                'badge' => 'bg-amber-50 text-amber-700 border-amber-200',
                'badge_bs' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                'icon' => 'fas fa-minus-circle',
                'color' => '#f59e0b',
                'signo' => '-',
                'clase_signo' => 'text-warning'
            ],
            'anulacion_venta' => [
                'label' => 'Anulación de Venta (Devolución)',
                'badge' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                'badge_bs' => 'bg-info-subtle text-info border border-info-subtle',
                'icon' => 'fas fa-undo-alt',
                'color' => '#06b6d4',
                'signo' => '+',
                'clase_signo' => 'text-success'
            ],
            'anulacion_compra' => [
                'label' => 'Anulación de Compra',
                'badge' => 'bg-rose-50 text-rose-700 border-rose-200',
                'badge_bs' => 'bg-danger-subtle text-danger border border-danger-subtle',
                'icon' => 'fas fa-ban',
                'color' => '#f43f5e',
                'signo' => '-',
                'clase_signo' => 'text-danger'
            ],
            'inicial' => [
                'label' => 'Stock Inicial',
                'badge' => 'bg-gray-50 text-gray-700 border-gray-200',
                'badge_bs' => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                'icon' => 'fas fa-flag-checkered',
                'color' => '#64748b',
                'signo' => '+',
                'clase_signo' => 'text-primary'
            ],
            default => [
                'label' => ucfirst(str_replace('_', ' ', $this->tipo)),
                'badge' => 'bg-gray-50 text-gray-700 border-gray-200',
                'badge_bs' => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                'icon' => 'fas fa-exchange-alt',
                'color' => '#64748b',
                'signo' => '',
                'clase_signo' => 'text-secondary'
            ]
        };
    }
}
