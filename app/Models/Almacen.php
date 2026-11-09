<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    use HasFactory;

    protected $table = 'almacenes';

    protected $fillable = [
        'codigo',
        'nombre',
        'direccion',
        'ciudad',
        'responsable',
        'telefono',
        'descripcion',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * 🔗 Relación: Un almacén tiene muchos productos
     */
    public function productos()
    {
        return $this->hasMany(Producto::class, 'almacen_id');
    }

    /**
     * Generar código sugerido único (ej: ALM-01, ALM-02)
     */
    public static function generarCodigo(): string
    {
        $ultimoId = self::max('id') ?? 0;
        return 'ALM-' . str_pad($ultimoId + 1, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Total de unidades de productos en este almacén
     */
    public function getTotalStockAttribute(): int
    {
        return (int) $this->productos()->sum('stock');
    }

    /**
     * Valor total del inventario en este almacén (costo de compra)
     */
    public function getValorInventarioAttribute(): float
    {
        return (float) $this->productos()->selectRaw('SUM(stock * precio_compra) as total')->value('total') ?? 0;
    }

    /**
     * Valor proyectado de venta en este almacén
     */
    public function getValorVentaAttribute(): float
    {
        return (float) $this->productos()->selectRaw('SUM(stock * precio_venta) as total')->value('total') ?? 0;
    }
}
