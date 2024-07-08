<?php

namespace App\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BackupService
{
    public function backup($encryptionPassword)
    {
        $backupDir = storage_path('app/backups'); // Directorio de backups
        $timestamp = now()->format('Y-m-d_H-i-s');
        $backupFileName = $timestamp . '_backup.sql';
        $backupFilePath = $backupDir . '/' . $backupFileName;
        $encryptedBackupFilePath = $backupDir . '/' . $timestamp . '_backup.sql.enc';
    
        // Comando para realizar el backup completo de MySQL (estructura y datos)
        $dbHost = Session::get('db_host');
        $dbUser = Session::get('db_username');
        $dbName = Session::get('db_database');
        $mysqldumpPath = 'C:\xampp\mysql\bin\mysqldump.exe'; // Ruta absoluta a mysqldump
    
        $command = sprintf(
            '%s --host=%s --user=%s --databases %s > %s',
            escapeshellarg($mysqldumpPath),
            escapeshellarg($dbHost),
            escapeshellarg($dbUser),
            escapeshellarg($dbName),
            escapeshellarg($backupFilePath)
        );
    
        try {
            // Ejecuta el comando del sistema operativo
            $output = null;
            $resultCode = null;
            exec($command, $output, $resultCode);
    
            if ($resultCode !== 0) {
                Log::error("mysqldump command failed with code $resultCode and output: " . implode("\n", $output));
                throw new Exception("Failed to create backup. Command exited with code $resultCode.");
            }
    
            // Encripta el archivo de respaldo
            $encryptCommand = sprintf(
                'openssl enc -aes-256-cbc -salt -in %s -out %s -k %s',
                escapeshellarg($backupFilePath),
                escapeshellarg($encryptedBackupFilePath),
                escapeshellarg($encryptionPassword)
            );
    
            exec($encryptCommand, $output, $resultCode);
    
            if ($resultCode !== 0) {
                Log::error("Encryption command failed with code $resultCode and output: " . implode("\n", $output));
                throw new Exception("Failed to encrypt backup. Command exited with code $resultCode.");
            }
    
            // Elimina el archivo de respaldo sin encriptar
            unlink($backupFilePath);
    
            // Registra el resultado del comando
            Log::info('Backup created and encrypted: ' . $encryptedBackupFilePath);
    
            return ['success' => true, 'output' => 'Backup created and encrypted successfully.'];
        } catch (Exception $e) {
            // Registra el error en el log
            Log::error('Backup failed: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
        
    }

public function restoreBackup($password, $backupFile)
{
    $backupFilePath = storage_path('app/backups/' . $backupFile);
    $decryptedBackupFilePath = storage_path('app/backups/' . str_replace('.enc', '', $backupFile));

    // Asegúrate de que el archivo de respaldo existe
    if (!file_exists($backupFilePath)) {
        return ['success' => false, 'error' => 'Backup file does not exist.'];
    }

    // Desencripta el archivo de respaldo
    $decryptCommand = sprintf(
        'openssl enc -aes-256-cbc -d -in %s -out %s -k %s',
        escapeshellarg($backupFilePath),
        escapeshellarg($decryptedBackupFilePath),
        escapeshellarg($password)
    );

    try {
        // Ejecuta el comando de desencriptación
        $output = null;
        $resultCode = null;
        exec($decryptCommand, $output, $resultCode);

        if ($resultCode !== 0) {
            Log::error("Decryption command failed with code $resultCode and output: " . implode("\n", $output));
            throw new Exception("Failed to decrypt backup. Command exited with code $resultCode.");
        }

        // Comando para restaurar el backup de MySQL
        $dbHost = Session::get('db_host');
        $dbUser = Session::get('db_username');
        $dbName = Session::get('db_database');
        $mysqlPath = 'C:\xampp\mysql\bin\mysql.exe'; // Ruta absoluta a mysql

        $command = sprintf(
            '%s --host=%s --user=%s %s < %s',
            escapeshellarg($mysqlPath),
            escapeshellarg($dbHost),
            escapeshellarg($dbUser),
            escapeshellarg($dbName),
            escapeshellarg($decryptedBackupFilePath)
        );

        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            Log::error("mysql command failed with code $resultCode and output: " . implode("\n", $output));
            throw new Exception("Failed to restore backup. Command exited with code $resultCode.");
        }

        // Elimina el archivo de respaldo desencriptado
        unlink($decryptedBackupFilePath);

        // Registra el resultado del comando
        Log::info('Backup restored: ' . $backupFilePath);

        return ['success' => true, 'output' => 'Backup restored successfully.'];
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

