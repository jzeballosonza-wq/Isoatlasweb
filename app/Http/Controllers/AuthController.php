<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\BitacoraHelper;

class AuthController extends Controller
{
    // Mostrar login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Procesar login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
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

        return back()->with('error', 'Credenciales incorrectas');
    }

    // Mostrar registro
    public function showRegister()
    {
        return view('auth.register');
    }

    // Procesar registro
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        Auth::login($user);

        BitacoraHelper::registrar(
            'Usuarios',
            'Registro',
            'Se registró el usuario: ' . $user->name
        );

        return redirect('/dashboard');
    }

    // Logout
    public function logout(Request $request)
    {
        BitacoraHelper::registrar(
            'Autenticación',
            'Logout',
            'El usuario cerró sesión'
        );

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}