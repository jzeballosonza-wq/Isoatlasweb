<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedors';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'email',
        'contacto',
        'celular'
    ];

    /**
     * 🔗 Relación: Un proveedor tiene muchas órdenes de compra
     */
    public function compras()
    {
        return $this->hasMany(Compra::class, 'proveedor_id');
    }

    /**
     * Total de compras registradas con este proveedor
     */
    public function getTotalComprasAttribute(): int
    {
        return $this->compras()->count();
    }

    /**
     * Monto total histórico invertido con este proveedor (en Bs.)
     */
    public function getTotalInvertidoAttribute(): float
    {
        return (float) ($this->compras()->sum('total') ?? 0);
    }

    /**
     * Última compra registrada con este proveedor
     */
    public function getUltimaCompraAttribute()
    {
        return $this->compras()->latest('fecha')->first();
    }

    /**
     * Obtener iniciales para el avatar (ej: "Importadora Acer" -> "IA")
     */
    public function getInicialesAttribute(): string
    {
        $words = preg_split("/[\s,--]+/", trim($this->nombre));
        $initials = '';
        foreach ($words as $w) {
            if (!empty($w)) {
                $initials .= mb_strtoupper(mb_substr($w, 0, 1));
            }
            if (mb_strlen($initials) >= 2) break;
        }
        return !empty($initials) ? $initials : 'PR';
    }

    /**
     * Obtener gradiente de color personalizado para el avatar
     */
    public function getAvatarGradientAttribute(): string
    {
        $gradients = [
            'linear-gradient(135deg, #0284c7 0%, #38bdf8 100%)',
            'linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%)',
            'linear-gradient(135deg, #059669 0%, #10b981 100%)',
            'linear-gradient(135deg, #d97706 0%, #f59e0b 100%)',
            'linear-gradient(135deg, #0d9488 0%, #14b8a6 100%)',
            'linear-gradient(135deg, #e11d48 0%, #f43f5e 100%)',
            'linear-gradient(135deg, #7c3aed 0%, #c084fc 100%)',
            'linear-gradient(135deg, #2563eb 0%, #60a5fa 100%)'
        ];
        $index = abs(crc32($this->nombre ?? 'proveedor')) % count($gradients);
        return $gradients[$index];
    }
}
