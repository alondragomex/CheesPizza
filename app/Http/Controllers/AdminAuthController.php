<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_logged_in') === true) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $correctPassword = env('ADMIN_PASSWORD', 'pizza123');

        if ($request->password === $correctPassword) {
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.dashboard')->with('success', '¡Sesión de administrador iniciada correctamente! Bienvenido.');
        }

        return back()->withErrors(['password' => 'Contraseña incorrecta. Por favor intente de nuevo.'])->withInput();
    }

    public function logout()
    {
        session()->forget('admin_logged_in');
        return redirect()->route('menu')->with('success', 'Has cerrado la sesión de administrador correctamente.');
    }
}
