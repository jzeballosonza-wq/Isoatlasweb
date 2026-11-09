<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Bitacora extends Model
{
    protected $fillable = [
        'user_id',
        'modulo',
        'accion',
        'descripcion',
        'ip'
    ];

    /**
     * 🔗 Relación con el usuario que ejecutó la acción
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * 🎨 Configuración visual y badge para la acción
     */
    public function getBadgeInfoAttribute(): array
    {
        $accionNorm = mb_strtolower(trim($this->accion ?? ''));

        if (str_contains($accionNorm, 'crear') || str_contains($accionNorm, 'registrar') || str_contains($accionNorm, 'nuevo')) {
            return [
                'bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'badge_class' => 'bg-success text-white',
                'color' => '#10b981',
                'icon' => 'fas fa-plus-circle'
            ];
        }

        if (str_contains($accionNorm, 'edit') || str_contains($accionNorm, 'actualiz') || str_contains($accionNorm, 'modific')) {
            return [
                'bg' => 'bg-blue-50 text-blue-700 border-blue-200',
                'badge_class' => 'bg-primary text-white',
                'color' => '#3b82f6',
                'icon' => 'fas fa-edit'
            ];
        }

        if (str_contains($accionNorm, 'elimin') || str_contains($accionNorm, 'borr') || str_contains($accionNorm, 'anul')) {
            return [
                'bg' => 'bg-rose-50 text-rose-700 border-rose-200',
                'badge_class' => 'bg-danger text-white',
                'color' => '#ef4444',
                'icon' => 'fas fa-trash-alt'
            ];
        }

        if (str_contains($accionNorm, 'login') || str_contains($accionNorm, 'inici') || str_contains($accionNorm, 'acceso')) {
            return [
                'bg' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                'badge_class' => 'bg-indigo text-white',
                'color' => '#6366f1',
                'icon' => 'fas fa-sign-in-alt'
            ];
        }

        if (str_contains($accionNorm, 'logout') || str_contains($accionNorm, 'cerr') || str_contains($accionNorm, 'salid')) {
            return [
                'bg' => 'bg-slate-50 text-slate-700 border-slate-200',
                'badge_class' => 'bg-secondary text-white',
                'color' => '#64748b',
                'icon' => 'fas fa-sign-out-alt'
            ];
        }

        if (str_contains($accionNorm, 'permis') || str_contains($accionNorm, 'segurid') || str_contains($accionNorm, 'rol')) {
            return [
                'bg' => 'bg-amber-50 text-amber-700 border-amber-200',
                'badge_class' => 'bg-warning text-dark',
                'color' => '#f59e0b',
                'icon' => 'fas fa-key'
            ];
        }

        if (str_contains($accionNorm, 'descarg') || str_contains($accionNorm, 'export') || str_contains($accionNorm, 'backup')) {
            return [
                'bg' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                'badge_class' => 'bg-info text-white',
                'color' => '#06b6d4',
                'icon' => 'fas fa-download'
            ];
        }

        if (str_contains($accionNorm, 'restaur') || str_contains($accionNorm, 'subir')) {
            return [
                'bg' => 'bg-purple-50 text-purple-700 border-purple-200',
                'badge_class' => 'bg-purple text-white',
                'color' => '#8b5cf6',
                'icon' => 'fas fa-history'
            ];
        }

        return [
            'bg' => 'bg-gray-50 text-gray-700 border-gray-200',
            'badge_class' => 'bg-secondary text-white',
            'color' => '#64748b',
            'icon' => 'fas fa-info-circle'
        ];
    }

    /**
     * 🏷️ Icono y color por módulo
     */
    public function getModuloInfoAttribute(): array
    {
        $modNorm = mb_strtolower(trim($this->modulo ?? ''));

        if (str_contains($modNorm, 'product') || str_contains($modNorm, 'laptop') || str_contains($modNorm, 'inventar')) {
            return ['icon' => 'fas fa-boxes-stacked', 'color' => '#6366f1', 'badge' => 'text-primary'];
        }
        if (str_contains($modNorm, 'vent')) {
            return ['icon' => 'fas fa-cash-register', 'color' => '#10b981', 'badge' => 'text-success'];
        }
        if (str_contains($modNorm, 'compr')) {
            return ['icon' => 'fas fa-shopping-cart', 'color' => '#3b82f6', 'badge' => 'text-info'];
        }
        if (str_contains($modNorm, 'client')) {
            return ['icon' => 'fas fa-user-friends', 'color' => '#0ea5e9', 'badge' => 'text-cyan'];
        }
        if (str_contains($modNorm, 'proveedor')) {
            return ['icon' => 'fas fa-truck-loading', 'color' => '#f59e0b', 'badge' => 'text-warning'];
        }
        if (str_contains($modNorm, 'categor')) {
            return ['icon' => 'fas fa-tags', 'color' => '#8b5cf6', 'badge' => 'text-purple'];
        }
        if (str_contains($modNorm, 'usuari') || str_contains($modNorm, 'user')) {
            return ['icon' => 'fas fa-users-cog', 'color' => '#475569', 'badge' => 'text-dark'];
        }
        if (str_contains($modNorm, 'rol') || str_contains($modNorm, 'permis')) {
            return ['icon' => 'fas fa-shield-alt', 'color' => '#e11d48', 'badge' => 'text-danger'];
        }
        if (str_contains($modNorm, 'backup') || str_contains($modNorm, 'copia') || str_contains($modNorm, 'seguridad')) {
            return ['icon' => 'fas fa-database', 'color' => '#d97706', 'badge' => 'text-warning'];
        }
        if (str_contains($modNorm, 'autentic') || str_contains($modNorm, 'auth') || str_contains($modNorm, 'login')) {
            return ['icon' => 'fas fa-lock', 'color' => '#059669', 'badge' => 'text-success'];
        }

        return ['icon' => 'fas fa-layer-group', 'color' => '#64748b', 'badge' => 'text-secondary'];
    }

    /**
     * ⏱️ Formateo de fecha y hora
     */
    public function getFechaFormateadaAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('d/m/Y H:i:s') : '-';
    }

    /**
     * ⏳ Tiempo relativo ("Hace 5 minutos")
     */
    public function getTiempoRelativoAttribute(): string
    {
        return $this->created_at ? $this->created_at->diffForHumans() : '-';
    }
}
