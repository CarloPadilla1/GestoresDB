<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'db_host'     => 'required|string',
            'db_port'     => 'required|string',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
        ]);

        // Guarda las credenciales en la sesión
        Session::put('db_host', $request->db_host);
        Session::put('db_port', $request->db_port);
        Session::put('db_database', $request->db_database);
        Session::put('db_username', $request->db_username);
        Session::put('db_password', $request->db_password);

        return redirect()->route('dashboard'); // Cambia 'dashboard' por tu ruta deseada
    }

    public function logout()
    {
        // Elimina las credenciales de la sesión
        Session::forget('db_host');
        Session::forget('db_port');
        Session::forget('db_database');
        Session::forget('db_username');
        Session::forget('db_password');

        return redirect()->route('login');
    }
}
