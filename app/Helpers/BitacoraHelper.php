<?php

namespace App\Helpers;

use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;

class BitacoraHelper
{
    /**
     * Registrar una entrada de auditoría en la bitácora
     *
     * @param string $modulo Nombre del módulo afectado
     * @param string $accion Tipo de acción efectuada (Crear, Editar, Eliminar, etc.)
     * @param string|null $descripcion Detalles específicos del evento
     * @param int|null $userId ID del usuario (opcional, por defecto Auth::id())
     * @param string|null $ip Dirección IP (opcional, por defecto request()->ip())
     * @return \App\Models\Bitacora
     */
    public static function registrar(
        string $modulo,
        string $accion,
        ?string $descripcion = null,
        ?int $userId = null,
        ?string $ip = null
    ): Bitacora {
        $finalUserId = $userId ?: (Auth::check() ? Auth::id() : null);
        $finalIp = $ip ?: request()->ip();

        return Bitacora::create([
            'user_id'     => $finalUserId,
            'modulo'      => $modulo,
            'accion'      => $accion,
            'descripcion' => $descripcion,
            'ip'          => $finalIp,
        ]);
    }
}