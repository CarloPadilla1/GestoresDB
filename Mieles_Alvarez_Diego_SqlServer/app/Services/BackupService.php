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
        $logFilename = $timestamp . '_backup.log';
        $username = Session::get('db_username');
        $database = Session::get('db_database'); // Reemplaza con el nombre de tu base de datos
        $backupDir = 'C:\\backups'; // Reemplaza con la ruta de tu directorio de backups

        // Define los comandos de backup permitidos
        $backupCommands = [
            'full' => sprintf(
                "sqlcmd -S localhost -U %s -P %s -Q \"BACKUP DATABASE [%s] TO DISK='%s\\%s_full.bak' WITH INIT\"",
                $username,
                $password,
                $database,
                $backupDir,
                $timestamp
            ),
            'differential' => sprintf(
                "sqlcmd -S localhost -U %s -P %s -Q \"BACKUP DATABASE [%s] TO DISK='%s\\%s_diff.bak' WITH DIFFERENTIAL, INIT\"",
                $username,
                $password,
                $database,
                $backupDir,
                $timestamp
            ),
            'transaction' => sprintf(
                "sqlcmd -S localhost -U %s -P %s -Q \"IF NOT EXISTS (SELECT * FROM msdb.dbo.backupset WHERE database_name='%s' AND type='D') BEGIN BACKUP DATABASE [%s] TO DISK='%s\\%s_initial_full.bak' WITH INIT; END; ALTER DATABASE [%s] SET RECOVERY FULL; BACKUP LOG [%s] TO DISK='%s\\%s_trans.trn' WITH INIT; ALTER DATABASE [%s] SET RECOVERY SIMPLE;\"",
                $username,
                $password,
                $database,
                $database,
                $backupDir,
                $timestamp,
                $database,
                $database,
                $backupDir,
                $timestamp,
                $database
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
        $username = Session::get('db_username');
        $database = Session::get('db_database');
        $backupDir = 'C:\\backups'; // Asegúrate de que la ruta exista y sea accesible
    
        // Verifica que el archivo de respaldo existe
        if (!file_exists($backupDir . '\\' . $backupFile)) {
            return ['success' => false, 'error' => 'Backup file does not exist.'];
        }
    
        // Comando para establecer la base de datos en modo usuario único
        $setSingleUserCommand = sprintf(
            "sqlcmd -S localhost -U %s -P %s -Q \"ALTER DATABASE [%s] SET SINGLE_USER WITH ROLLBACK IMMEDIATE\"",
            $username,
            $password,
            $database
        );
    
        // Comando de restauración
        $restoreCommand = sprintf(
            "sqlcmd -S localhost -U %s -P %s -Q \"RESTORE DATABASE [%s] FROM DISK='%s\\%s' WITH REPLACE, RECOVERY\"",
            $username,
            $password,
            $database,
            $backupDir,
            $backupFile
        );
    
        // Comando para volver a establecer la base de datos en modo multiusuario
        $setMultiUserCommand = sprintf(
            "sqlcmd -S localhost -U %s -P %s -Q \"ALTER DATABASE [%s] SET MULTI_USER\"",
            $username,
            $password,
            $database
        );
    
        try {
            // Poner la base de datos en modo usuario único para desconectar a todos los usuarios
            shell_exec($setSingleUserCommand);
    
            // Restaurar la base de datos
            $output = shell_exec($restoreCommand);
    
            // Volver a poner la base de datos en modo multiusuario
            shell_exec($setMultiUserCommand);
    
            // Verificar el resultado del comando
            if ($output === null) {
                throw new Exception("Failed to execute restore command.");
            }
    
            // Registra el resultado del comando
            Log::info('Restore output: ' . $output);
    
            return ['success' => true, 'output' => $output];
        } catch (\Exception $e) {
            // Registra el error en el log
            Log::error('Restore failed: ' . $e->getMessage());
    
            // Intentar poner la base de datos de nuevo en modo multiusuario en caso de fallo
            shell_exec($setMultiUserCommand);
    
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    

}
