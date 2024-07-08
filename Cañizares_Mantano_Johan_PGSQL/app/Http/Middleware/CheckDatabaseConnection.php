<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckDatabaseConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Obtén las credenciales de la base de datos desde la sesión
        $dbHost = Session::get('db_host');
        $dbPort = Session::get('db_port');
        $dbDatabase = Session::get('db_database');
        $dbUsername = Session::get('db_username');
        $dbPassword = Session::get('db_password');

        if ($dbHost && $dbDatabase && $dbUsername && $dbPassword) {
            // Configura la conexión de la base de datos dinámicamente
            Config::set('database.connections.pgsql', [
                'driver'    => 'pgsql',
                'host'      => $dbHost,
                'port'      => $dbPort,
                'database'  => $dbDatabase,
                'username'  => $dbUsername,
                'password'  => $dbPassword,
                'charset'   => 'utf8',
                'prefix'    => '',
                'schema'    => 'public',
                'sslmode'   => 'prefer',
            ]);

            try {
                // Intenta conectar a la base de datos
                DB::connection('pgsql')->getPdo();

                // Configura la conexión predeterminada para Laravel
                Config::set('database.default', 'pgsql');

                // Consulta para verificar los privilegios de DBA
                $privileges = DB::connection('pgsql')->select("
                    SELECT COUNT(*) AS count
                    FROM pg_roles
                    WHERE rolname = ? AND rolsuper = true
                ", [$dbUsername])[0]->count > 0;

                // Guarda el resultado en la sesión
                Session::put('has_dba_role', $privileges);
            } catch (\Exception $e) {
                // Si la conexión falla, redirige a la vista de inicio de sesión
                return redirect()->route('login')->withErrors(['db_connection' => 'Unable to connect to the database. Please check your credentials. ' . $e->getMessage()]);
            }
        } else {
            // Si no hay credenciales en la sesión, redirige a la vista de inicio de sesión
            return redirect()->route('login');
        }

        return $next($request);
    }
}
