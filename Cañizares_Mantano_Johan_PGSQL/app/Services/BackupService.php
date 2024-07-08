<?php

namespace App\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class BackupService
{
    public function __construct()
    {
        //
    }


    public function backup($password, $backupType)
    {
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $username = Session::get('db_username');
        $host = 'localhost';
        $port = '5432';
        $database  = Session::get('db_database');
        $backupDir = 'C:\\backups'; // Reemplaza con la ruta de tu directorio de backups





// Define los comandos de backup permitidos
    $backupCommands = [
        'pg_dump' => sprintf(
            'pg_dump --dbname=postgresql://%s:%s@%s:%s/%s > "%s\\%s_backup.sql"',
            $username,
            $password,
            $host,
            $port,
            $database,
            $backupDir,
            $timestamp
        ),
    ];

        // Verifica que el tipo de backup sea válido
        if (!array_key_exists($backupType, $backupCommands)) {
            return ['success' => false, 'error' => 'Invalid backup type'];
        }

        // Obtiene el comando correspondiente al tipo de backup
        $command = $backupCommands[$backupType];

        try {
            // Ejecuta el comando del sistema operativo
            $output = shell_exec($command);

            // Registra el resultado del comando
            Log::info('Backup output: ' . $output);

            return ['success' => true, 'output' => $output];
        } catch (Exception $e) {
            // Registra el error en el log
            Log::error('Backup failed: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function getUserTables($username)
    {
        // Obtiene las tablas del usuario
        $tables = DB::select('SELECT table_name FROM all_tables WHERE owner = ?', [$username]);

        // Convierte las tablas a un array de strings
        $tableNames = array_map(function ($table) {
            return $table->table_name;
        }, $tables);

        // Retorna las tablas separadas por coma
        return implode(',', $tableNames);

    }

    public function restoreBackup($password, $backupFile)
    {
        $logFilename = 'restore.log';
        $username = Session::get('db_username'); // Reemplaza con tu nombre de usuario de PostgreSQL
        $database = Session::get('db_database'); // Reemplaza con el nombre de tu base de datos
        $backupDir = 'C:\\backups'; // Ruta de tu directorio de backups

        // Verificar que el archivo de respaldo existe
        if (!file_exists($backupDir . '\\' . $backupFile)) {
            return ['success' => false, 'error' => 'Backup file does not exist.'];
        }

        // Definir el comando de restauración con pg_restore
        $command = sprintf(
            'psql -U %s -d %s -f "%s\\%s" > "%s\\%s"',
            $username,
            $database,
            $backupDir,
            $backupFile,
            $backupDir,
            $logFilename
        );


        try {
            // Ejecutar el comando del sistema operativo
            $output = shell_exec($command);

            // Registrar el resultado del comando
            Log::info('Restore output: ' . $output);

            return ['success' => true, 'output' => $output];
        } catch (Exception $e) {
            // Registrar el error en el log
            Log::error('Restore failed: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }



    public function executeScript($script)
    {
        try {
            DB::beginTransaction();
            DB::unprepared($script);

            DB::commit();

            return ['success' => true];
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error executing script: ' . $e->getMessage());

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
