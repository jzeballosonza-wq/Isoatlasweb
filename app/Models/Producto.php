<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'codigo',
        'categoria_id',
        'almacen_id',
        'nombre',
        'descripcion',
        'imagen',
        'stock',
        'stock_minimo',
        'stock_maximo',
        'ubicacion_estante',
        'precio_compra',
        'precio_venta',
        'fecha_ingreso'
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'stock' => 'integer'
    ];

    // 🔗 Relación con categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // 🔗 Relación con almacén donde se encuentra
    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'almacen_id');
    }

    // 🔗 Relación con detalles de venta
    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    // 🔗 Relación con detalles de compra
    public function detalleCompras()
    {
        return $this->hasMany(DetalleCompra::class);
    }

    // 🔗 Relación con movimientos de inventario (Kardex)
    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class)->latest('id');
    }


    /**
     * Inversión monetaria actual en este producto (Stock * Costo Compra)
     */
    public function getValorInversionAttribute(): float
    {
        return (float) ($this->stock * $this->precio_compra);
    }

    /**
     * Valor monetario proyectado a precio de venta (Stock * Precio Venta)
     */
    public function getValorVentaAttribute(): float
    {
        return (float) ($this->stock * $this->precio_venta);
    }

    /**
     * Ganancia total proyectada en almacén
     */
    public function getGananciaPotencialAttribute(): float
    {
        return (float) max(0, $this->valor_venta - $this->valor_inversion);
    }

    /**
     * Margen neto de ganancia por unidad (en Bs.)
     */
    public function getGananciaAttribute(): float
    {
        return (float) max(0, $this->precio_venta - $this->precio_compra);
    }

    /**
     * Porcentaje de rentabilidad / markup sobre el costo de compra
     */
    public function getMargenPorcentajeAttribute(): float
    {
        if ($this->precio_compra > 0) {
            return round((($this->precio_venta - $this->precio_compra) / $this->precio_compra) * 100, 1);
        }
        return 0.0;
    }

    /**
     * Estado del stock actual comparado con umbrales
     */
    public function getEstadoStockAttribute(): array
    {
        $min = $this->stock_minimo ?? 5;
        
        if ($this->stock <= 0) {
            return [
                'clave' => 'agotado',
                'label' => 'Agotado',
                'badge_class' => 'bg-danger-subtle text-danger border border-danger-subtle',
                'color' => '#ef4444',
                'icon' => 'fas fa-times-circle'
            ];
        } elseif ($this->stock <= $min) {
            return [
                'clave' => 'bajo',
                'label' => 'Stock Bajo',
                'badge_class' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                'color' => '#f59e0b',
                'icon' => 'fas fa-exclamation-triangle'
            ];
        } else {
            return [
                'clave' => 'optimo',
                'label' => 'Stock Óptimo',
                'badge_class' => 'bg-success-subtle text-success border border-success-subtle',
                'color' => '#10b981',
                'icon' => 'fas fa-check-circle'
            ];
        }
    }

    /**
     * Porcentaje de llenado de inventario respecto al máximo
     */
    public function getNivelStockPorcentajeAttribute(): int
    {
        $max = $this->stock_maximo && $this->stock_maximo > 0 ? $this->stock_maximo : max(50, $this->stock);
        return min(100, (int) round(($this->stock / $max) * 100));
    }

    /**
     * URL completa de la imagen o placeholder
     */
    public function getImagenUrlAttribute(): string
    {
        if ($this->imagen && file_exists(public_path('storage/' . $this->imagen))) {
            return asset('storage/' . $this->imagen);
        }
        return '';
    }
}