<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Bitacora;

class AuthenticatedSessionController extends Controller
{
    /**
     * Mostrar login
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Procesar login
     */
   public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    \App\Models\Bitacora::create([
        'user_id' => Auth::id(),
        'modulo' => 'Autenticación',
        'accion' => 'Login',
        'descripcion' => 'El usuario inició sesión',
        'ip' => $request->ip(),
    ]);

    return redirect()->intended(route('dashboard', absolute: false));
}
    /**
     * Cerrar sesión
     */
   public function destroy(Request $request): RedirectResponse
{
    \App\Models\Bitacora::create([
        'user_id' => Auth::id(),
        'modulo' => 'Autenticación',
        'accion' => 'Logout',
        'descripcion' => 'El usuario cerró sesión',
        'ip' => $request->ip(),
    ]);

    Auth::guard('web')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
}
}