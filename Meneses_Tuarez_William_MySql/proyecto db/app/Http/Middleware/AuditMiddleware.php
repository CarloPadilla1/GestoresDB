<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuditMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Lógica para registrar auditoría
        $excludedTables = ['audit_logs']; // Excluir la tabla de auditoría
        $changes = $request->all();
        $tableName = $request->route('name'); // Asumiendo que el nombre de la tabla se pasa como parámetro en la ruta

        if (!in_array($tableName, $excludedTables)) {
            DB::table('audit_logs')->insert([
                'user_id' => Auth::id(),
                'table_name' => $tableName,
                'changes' => json_encode($changes),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $response;
    }
}
