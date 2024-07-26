<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class AuditController extends Controller
{
    public function index()
    {
        // Aquí puedes modificar la consulta para adaptarla a SQL Server
        $tables = DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = 'your_database_schema'");
        return view('audits.index', compact('tables'));
    }

    public function show($table)
    {
        // Utiliza el nombre correcto de la tabla y asegúrate de que esté en minúsculas
        $audits = DB::table('Audit')
        ->where('TableName', $table) // Filtrar por el nombre de la tabla
        ->get();
        return view('audits.show', compact('audits', 'table'));
    }
    ////
    public function generateTriggersSql()
<<<<<<< HEAD
    {
        $sqlFilePath = storage_path('app/triggers.sql'); // Ruta donde se guardará el archivo SQL
    
        // Inicializar el contenido del archivo SQL
        $sqlContent = '';
    
        // Consultar todas las tablas de la base de datos excepto la tabla Audit
        $tables = DB::select("SELECT table_name FROM user_tables WHERE table_name <> 'AUDIT'");
    
        foreach ($tables as $table) {
            $tableName = $table->table_name;
    
    
            // $audit = "--Habilitar auditoria en la tabla $tableName\n";
            // $audit .= "AUDIT INSERT, UPDATE, DELETE ON $tableName BY ACCESS;\n\n";
    
            // Generar el nombre del archivo para el trigger de cada tabla
            $deleteTrigger = "-- Trigger DELETE for table $tableName\n";
            $deleteTrigger .= $this->generateTriggerSql($tableName, 'DELETE');
    
            $insertTrigger = "-- Trigger INSERT for table $tableName\n";
            $insertTrigger .= $this->generateTriggerSql($tableName, 'INSERT');
    
            $updateTrigger = "-- Trigger UPDATE for table $tableName\n";
            $updateTrigger .= $this->generateTriggerSql($tableName, 'UPDATE');
    
            // Concatenar los triggers al contenido del archivo SQL
            $sqlContent .= $deleteTrigger . "\n";
            $sqlContent .= $insertTrigger . "\n";
            $sqlContent .= $updateTrigger . "\n";
            // $sqlContent .= $audit . "\n";
        }
    
        // Guardar el contenido en el archivo SQL
        File::put($sqlFilePath, $sqlContent);
    
        // Descargar el archivo SQL
        return response()->download($sqlFilePath, 'triggers.sql')->deleteFileAfterSend();
    }

=======
{
    $sqlFilePath = storage_path('app/triggers.sql'); // Ruta donde se guardará el archivo SQL

    // Inicializar el contenido del archivo SQL
    $sqlContent = '';

    // Consultar todas las tablas de la base de datos excepto la tabla Audit
    $tables = DB::select("SELECT table_name FROM user_tables WHERE table_name <> 'AUDIT'");

    foreach ($tables as $table) {
        $tableName = $table->table_name;

        // Generar el nombre del archivo para el trigger de cada tabla
        $deleteTrigger = "-- Trigger DELETE for table $tableName\n";
        $deleteTrigger .= $this->generateTriggerSql($tableName, 'DELETE');

        $insertTrigger = "-- Trigger INSERT for table $tableName\n";
        $insertTrigger .= $this->generateTriggerSql($tableName, 'INSERT');

        $updateTrigger = "-- Trigger UPDATE for table $tableName\n";
        $updateTrigger .= $this->generateTriggerSql($tableName, 'UPDATE');

        // Concatenar los triggers al contenido del archivo SQL
        $sqlContent .= $deleteTrigger . "\n";
        $sqlContent .= $insertTrigger . "\n";
        $sqlContent .= $updateTrigger . "\n";
    }

    // Guardar el contenido en el archivo SQL
    File::put($sqlFilePath, $sqlContent);

    // Descargar el archivo SQL
    return response()->download($sqlFilePath, 'triggers.sql')->deleteFileAfterSend();
}

>>>>>>> 639eefa23fa0ed553a0431da0b523f95b50381b6
// Función para generar el SQL de un trigger específico
private function generateTriggerSql($tableName, $actionType) {
    $timing = 'AFTER';
    if ($actionType === 'INSERT') {
        $timing = 'BEFORE';
    }

    $triggerSql = "
    CREATE OR REPLACE TRIGGER trg_{$tableName}_{$actionType}
    {$timing} {$actionType} ON {$tableName}
    FOR EACH ROW
    BEGIN
        INSERT INTO AUDIT (TableName, ActionType, UserName, ActionDetails)
        VALUES ('{$tableName}', '{$actionType}', USER, '{$actionType}d record');
    END;
    ";
    return $triggerSql;
}
<<<<<<< HEAD
public function generateLogsPdf()
{
    $items = DB::table('dba_audit_trail')->get();
    $pdf = app('dompdf.wrapper');
    $pdf->loadView('logs.pdf', compact('items'))
        ->setPaper('a4', 'landscape');
    return $pdf->download('logs.pdf');
}
=======

>>>>>>> 639eefa23fa0ed553a0431da0b523f95b50381b6
}
