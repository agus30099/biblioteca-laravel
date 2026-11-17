<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // ✅ Redirigir según el rol
            if (auth()->user()->role == 'admin') {
                return redirect()->intended('dashboard')->with('success', '¡Bienvenido Administrador!');
            } else {
                // Los lectores van directo a sus reservas
                return redirect()->intended('reservas')->with('success', '¡Bienvenido a la Biblioteca!');
            }
        }

        return back()->withErrors(['email' => 'Las credenciales no coinciden.']);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

        public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
        // 1. Validar los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed', // confirmed busca un campo password_confirmation
        ]);

        // 2. Crear el usuario con rol de 'lector'
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'lector', // Se registran como lectores
        ]);

        // 3. Iniciar sesión automáticamente
        \Illuminate\Support\Facades\Auth::login($user);

        return redirect()->route('dashboard')->with('success', '¡Bienvenido a la biblioteca! Tu cuenta ha sido creada.');
    }
}