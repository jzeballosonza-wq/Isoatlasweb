<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'rols';

    /**
     * Campos permitidos
     */
    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    /**
     * 🔗 Relación: Un rol tiene muchos usuarios
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * 🔗 Relación: Un rol tiene muchos permisos
     */
    public function permisos()
    {
        return $this->belongsToMany(
            Permiso::class,
            'rol_permiso',
            'rol_id',
            'permiso_id'
        );
    }

    /**
     * Verificar si es el rol Administrador principal
     */
    public function esAdmin(): bool
    {
        return stripos($this->nombre, 'admin') !== false;
    }

    /**
     * Verificar si tiene un permiso específico
     */
    public function tienePermiso($nombre): bool
    {
        return $this->permisos->contains('nombre', $nombre);
    }

    /**
     * Badge de color según el nombre del rol
     */
    public function getColorBadgeAttribute(): array
    {
        $nombre = mb_strtolower($this->nombre);
        if (str_contains($nombre, 'admin')) {
            return [
                'bg' => 'linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%)',
                'text' => '#ffffff',
                'border' => '#4338ca',
                'pill_class' => 'badge-admin',
                'icon' => 'fas fa-user-shield'
            ];
        } elseif (str_contains($nombre, 'vendedor') || str_contains($nombre, 'ventas')) {
            return [
                'bg' => 'linear-gradient(135deg, #059669 0%, #10b981 100%)',
                'text' => '#ffffff',
                'border' => '#047857',
                'pill_class' => 'badge-vendedor',
                'icon' => 'fas fa-cash-register'
            ];
        } elseif (str_contains($nombre, 'supervisor') || str_contains($nombre, 'gerente')) {
            return [
                'bg' => 'linear-gradient(135deg, #d97706 0%, #f59e0b 100%)',
                'text' => '#ffffff',
                'border' => '#b45309',
                'pill_class' => 'badge-supervisor',
                'icon' => 'fas fa-user-tie'
            ];
        } elseif (str_contains($nombre, 'almacen') || str_contains($nombre, 'inventario')) {
            return [
                'bg' => 'linear-gradient(135deg, #0284c7 0%, #38bdf8 100%)',
                'text' => '#ffffff',
                'border' => '#0369a1',
                'pill_class' => 'badge-almacen',
                'icon' => 'fas fa-boxes'
            ];
        } else {
            return [
                'bg' => 'linear-gradient(135deg, #64748b 0%, #475569 100%)',
                'text' => '#ffffff',
                'border' => '#334155',
                'pill_class' => 'badge-default',
                'icon' => 'fas fa-user-tag'
            ];
        }
    }
}