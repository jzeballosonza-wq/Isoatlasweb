<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    /**
     * 🔗 Relación con Productos de la categoría
     */
    public function productos()
    {
        return $this->hasMany(Producto::class, 'categoria_id');
    }

    /**
     * 📊 Cantidad total de productos en esta categoría
     */
    public function getTotalProductosAttribute(): int
    {
        return $this->productos()->count();
    }

    /**
     * 📦 Stock físico acumulado en unidades
     */
    public function getTotalStockAttribute(): int
    {
        return (int) $this->productos()->sum('stock');
    }

    /**
     * 💰 Valor económico total del inventario en esta categoría (Costo de adquisición)
     */
    public function getValorInventarioAttribute(): float
    {
        return (float) ($this->productos()
            ->selectRaw('SUM(stock * precio_compra) as total_valor')
            ->value('total_valor') ?? 0);
    }

    /**
     * 🏷️ Iniciales para badge visual
     */
    public function getInicialesAttribute(): string
    {
        $palabras = explode(' ', trim($this->nombre));
        if (count($palabras) >= 2) {
            return strtoupper(substr($palabras[0], 0, 1) . substr($palabras[1], 0, 1));
        }
        return strtoupper(substr($this->nombre, 0, 2));
    }

    /**
     * 🎨 Gradiente determinista y armonioso
     */
    public function getAvatarGradientAttribute(): string
    {
        $gradients = [
            'linear-gradient(135deg, #6366f1 0%, #4338ca 100%)', // Indigo
            'linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%)', // Sky / Cyan
            'linear-gradient(135deg, #10b981 0%, #047857 100%)', // Emerald
            'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)', // Amber
            'linear-gradient(135deg, #ec4899 0%, #be185d 100%)', // Pink / Rose
            'linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%)', // Purple
            'linear-gradient(135deg, #14b8a6 0%, #0f766e 100%)', // Teal
            'linear-gradient(135deg, #f97316 0%, #c2410c 100%)', // Orange
        ];

        return $gradients[$this->id % count($gradients)] ?? $gradients[0];
    }

    /**
     * 🎯 Ícono dinámico según la temática de la categoría (Electrónica, Periféricos, Audio, PC, etc.)
     */
    public function getIconoAttribute(): string
    {
        $nombreLower = strtolower($this->nombre);

        // Periféricos específicos
        if (str_contains($nombreLower, 'teclad') || str_contains($nombreLower, 'keyboard')) {
            return 'fas fa-keyboard';
        }
        if (str_contains($nombreLower, 'mouse') || str_contains($nombreLower, 'raton')) {
            return 'fas fa-mouse';
        }
        if (str_contains($nombreLower, 'auricular') || str_contains($nombreLower, 'headset') || str_contains($nombreLower, 'headphone') || str_contains($nombreLower, 'diadema')) {
            return 'fas fa-headphones';
        }
        if (str_contains($nombreLower, 'camara') || str_contains($nombreLower, 'cámara') || str_contains($nombreLower, 'webcam') || str_contains($nombreLower, 'video')) {
            return 'fas fa-video';
        }
        if (str_contains($nombreLower, 'monitor') || str_contains($nombreLower, 'pantalla') || str_contains($nombreLower, 'display')) {
            return 'fas fa-tv';
        }
        if (str_contains($nombreLower, 'microfon') || str_contains($nombreLower, 'micrófon') || str_contains($nombreLower, 'mic')) {
            return 'fas fa-microphone';
        }
        if (str_contains($nombreLower, 'parlante') || str_contains($nombreLower, 'altavoz') || str_contains($nombreLower, 'bocina') || str_contains($nombreLower, 'soundbar') || str_contains($nombreLower, 'audio')) {
            return 'fas fa-volume-up';
        }
        if (str_contains($nombreLower, 'disco') || str_contains($nombreLower, 'ssd') || str_contains($nombreLower, 'nvme') || str_contains($nombreLower, 'almacenamiento') || str_contains($nombreLower, 'usb') || str_contains($nombreLower, 'pendrive')) {
            return 'fas fa-hdd';
        }
        if (str_contains($nombreLower, 'componente') || str_contains($nombreLower, 'tarjeta') || str_contains($nombreLower, 'placa') || str_contains($nombreLower, 'ram') || str_contains($nombreLower, 'procesador') || str_contains($nombreLower, 'fuente') || str_contains($nombreLower, 'workstation')) {
            return 'fas fa-microchip';
        }
        if (str_contains($nombreLower, 'cable') || str_contains($nombreLower, 'adaptador') || str_contains($nombreLower, 'hub') || str_contains($nombreLower, 'cargador')) {
            return 'fas fa-plug';
        }
        if (str_contains($nombreLower, 'gam') || str_contains($nombreLower, 'juego') || str_contains($nombreLower, 'rog') || str_contains($nombreLower, 'tuf')) {
            return 'fas fa-gamepad';
        }
        if (str_contains($nombreLower, 'laptop') || str_contains($nombreLower, 'portatil') || str_contains($nombreLower, 'portátil') || str_contains($nombreLower, 'notebook') || str_contains($nombreLower, 'ultra')) {
            return 'fas fa-laptop';
        }
        if (str_contains($nombreLower, 'pc') || str_contains($nombreLower, 'computadora') || str_contains($nombreLower, 'escritorio') || str_contains($nombreLower, 'desktop')) {
            return 'fas fa-desktop';
        }
        if (str_contains($nombreLower, 'oficina') || str_contains($nombreLower, 'empresa') || str_contains($nombreLower, 'corporat')) {
            return 'fas fa-briefcase';
        }
        if (str_contains($nombreLower, 'estudi') || str_contains($nombreLower, 'escolar')) {
            return 'fas fa-graduation-cap';
        }
        if (str_contains($nombreLower, 'accesorio') || str_contains($nombreLower, 'periferic') || str_contains($nombreLower, 'periféric')) {
            return 'fas fa-boxes-stacked';
        }
        if (str_contains($nombreLower, 'repuesto') || str_contains($nombreLower, 'herramienta') || str_contains($nombreLower, 'servicio')) {
            return 'fas fa-tools';
        }

        return 'fas fa-box-open';
    }
}