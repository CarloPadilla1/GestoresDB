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

    if ($dbHost && $dbDatabase && $dbUsername) {
        // Configura la conexión de la base de datos dinámicamente para MySQL
        Config::set('database.connections.mysql_dynamic', [
            'driver'    => 'mysql',
            'host'      => $dbHost,
            'port'      => $dbPort,
            'database'  => $dbDatabase,
            'username'  => $dbUsername,
            'password'  => $dbPassword,
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => true,
            'engine'    => null,
        ]);

        try {
            // Intenta conectar a la base de datos
            DB::connection('mysql_dynamic')->getPdo();

            // Establece la conexión predeterminada a mysql_dynamic
            config(['database.default' => 'mysql_dynamic']);
            
            // Ajusta la consulta SQL según tus necesidades en MySQL
            $privileges = DB::select("
                SELECT COUNT(*) AS count FROM information_schema.user_privileges 
                WHERE GRANTEE = CONCAT('\'', :username, '\'@\'', :host, '\'')
                AND PRIVILEGE_TYPE = 'SUPER'
            ", ['username' => $dbUsername, 'host' => $dbHost])[0]->count > 0;

            // Guarda el resultado en la sesión
            Session::put('has_dba_role', $privileges);
        } catch (\Exception $e) {
            // Si la conexión falla, redirige a la vista de inicio de sesión
            return redirect()->route('login')->withErrors(['db_connection' => 'Unable to connect to the database. Please check your credentials. '.$e->getMessage()]);
        }
    } else {
        // Si no hay credenciales en la sesión, redirige a la vista de inicio de sesión
        return redirect()->route('login');
    }

    return $next($request);
}
}
