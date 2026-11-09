<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'fecha',
        'comprobante',
        'total',
        'caja_sesion_id'
    ];

    public function cajaSesion()
    {
        return $this->belongsTo(CajaSesion::class, 'caja_sesion_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}