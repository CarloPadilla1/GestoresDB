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
            Config::set('database.connections.sqlsrv', [
                'driver'    => 'sqlsrv',
                'host'      => $dbHost,
                'port'      => $dbPort,
                'database'  => $dbDatabase,
                'username'  => $dbUsername,
                'password'  => $dbPassword,
                'charset'   => 'utf8',
                'prefix'    => '',
                'prefix_indexes' => true,
            ]);

            try {
                // Intenta conectar a la base de datos
                DB::connection('sqlsrv')->getPdo();

                // Configura la conexión predeterminada para Laravel
                Config::set('database.default', 'sqlsrv');

                // Consulta para verificar los privilegios de DBA
                $privileges = DB::connection('sqlsrv')->select("
                    SELECT COUNT(*) AS count
                    FROM sys.database_role_members drm
                    JOIN sys.database_principals dp ON drm.role_principal_id = dp.principal_id
                    JOIN sys.database_principals dp2 ON drm.member_principal_id = dp2.principal_id
                    WHERE dp.name = 'db_owner' AND dp2.name = ?
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
