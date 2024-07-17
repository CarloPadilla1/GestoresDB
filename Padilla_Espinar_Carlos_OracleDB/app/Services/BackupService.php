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
        $backupDir = '/path/to/directory'; // Ruta absoluta al directorio de backups
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $logFilename = $timestamp . '_backup.log';
        $username = Session::get('db_username');

        // Define los comandos de backup permitidos
        $backupCommands = [
            'full' => sprintf(
                "expdp %s/%s FULL=Y DIRECTORY=DATA_PUMP_DIR DUMPFILE=%s_full_export.dmp LOGFILE=%s",
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($timestamp),
                escapeshellarg($logFilename)
            ),
            'user' => sprintf(
                "expdp %s/%s SCHEMAS=%s DIRECTORY=DATA_PUMP_DIR DUMPFILE=%s_user_export.dmp LOGFILE=%s",
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($username),
                escapeshellarg($timestamp),
                escapeshellarg($logFilename)
            ),
            'tables' => sprintf(
                "expdp %s/%s TABLES=%s DIRECTORY=DATA_PUMP_DIR DUMPFILE=%s_tables_export.dmp LOGFILE=%s",
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($this->getUserTables($username)), // Asumiendo que tienes una función para obtener las tablas del usuario
                escapeshellarg($timestamp),
                escapeshellarg($logFilename)
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
        $username = Session::get('db_username');

        // Asegúrate de que el archivo de respaldo existe


        // Define el comando de restauración
        $command = sprintf(
            "impdp %s/%s DIRECTORY=DATA_PUMP_DIR DUMPFILE=%s LOGFILE=%s",
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($backupFile),
            escapeshellarg($logFilename)
        );

        try {
            // Ejecuta el comando del sistema operativo
            $output = shell_exec($command);

            // Verifica el resultado del comando
            if ($output === null) {
                throw new Exception("Failed to execute restore command.");
            }

            // Registra el resultado del comando
            Log::info('Restore output: ' . $output);

            return ['success' => true, 'output' => $output];
        } catch (Exception $e) {
            // Registra el error en el log
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
