<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferenciaAlmacen extends Model
{
    use HasFactory;

    protected $table = 'transferencias_almacen';

    protected $fillable = [
        'codigo_transferencia',
        'producto_id',
        'almacen_origen_id',
        'almacen_destino_id',
        'user_id',
        'cantidad',
        'ubicacion_destino',
        'motivo',
        'fecha'
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'cantidad' => 'integer'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function almacenOrigen()
    {
        return $this->belongsTo(Almacen::class, 'almacen_origen_id');
    }

    public function almacenDestino()
    {
        return $this->belongsTo(Almacen::class, 'almacen_destino_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function generarCodigo(): string
    {
        $year = date('Y');
        $ultimo = self::whereYear('created_at', $year)->latest('id')->first();

        if (!$ultimo) {
            return "TRF-{$year}-0001";
        }

        $partes = explode('-', $ultimo->codigo_transferencia);
        $correlativo = isset($partes[2]) ? (int)$partes[2] + 1 : $ultimo->id + 1;

        return sprintf("TRF-%s-%04d", $year, $correlativo);
    }
}
