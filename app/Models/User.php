<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Campos que se pueden guardar
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol_id'
    ];

    /**
     * Campos ocultos
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * 🔗 Relación: Usuario pertenece a un rol
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }

    /**
     * 🔗 Relación: Bitácoras generadas por el usuario
     */
    public function bitacoras()
    {
        return $this->hasMany(Bitacora::class, 'user_id');
    }

    /**
     * Verificar si el usuario tiene un permiso específico
     */
    public function tienePermiso($permiso)
    {
        if ($this->esAdmin()) {
            return true;
        }

        if (!$this->rol) {
            return false;
        }

        return $this->rol->permisos()
            ->where('nombre', $permiso)
            ->exists();
    }

    /**
     * Verificar si es Administrador
     */
    public function esAdmin(): bool
    {
        return $this->rol && stripos($this->rol->nombre, 'admin') !== false;
    }

    /**
     * Obtener iniciales del usuario (ej: "Juan Perez" -> "JP")
     */
    public function getInicialesAttribute(): string
    {
        $words = preg_split("/[\s,--]+/", trim($this->name));
        $initials = '';
        foreach ($words as $w) {
            if (!empty($w)) {
                $initials .= mb_strtoupper(mb_substr($w, 0, 1));
            }
            if (mb_strlen($initials) >= 2) break;
        }
        return !empty($initials) ? $initials : 'US';
    }

    /**
     * Obtener gradiente de color para el avatar
     */
    public function getAvatarGradientAttribute(): string
    {
        $gradients = [
            'linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%)',
            'linear-gradient(135deg, #059669 0%, #10b981 100%)',
            'linear-gradient(135deg, #0284c7 0%, #38bdf8 100%)',
            'linear-gradient(135deg, #d97706 0%, #f59e0b 100%)',
            'linear-gradient(135deg, #dc2626 0%, #ef4444 100%)',
            'linear-gradient(135deg, #7c3aed 0%, #a855f7 100%)',
            'linear-gradient(135deg, #0d9488 0%, #14b8a6 100%)',
            'linear-gradient(135deg, #e11d48 0%, #f43f5e 100%)',
        ];
        $index = abs(crc32($this->name ?? 'user')) % count($gradients);
        return $gradients[$index];
    }
}