<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceUsers
{
    public function __construct()
    {
        //
    }

    public function create($data)
{
    try {
        DB::beginTransaction();

        $user = $data['username'];
        $password = $data['password'];
        $privileges = $data['privileges'];

        // Crear el usuario
        $sql = "CREATE USER '$user'@'localhost' IDENTIFIED BY '$password'";
        DB::statement($sql);

        // Asignar privilegios
        $privilegeColumns = [
            'Select_priv' => 'SELECT',
            'Insert_priv' => 'INSERT',
            'Update_priv' => 'UPDATE',
            'Delete_priv' => 'DELETE',
            'Create_priv' => 'CREATE',
            'Drop_priv' => 'DROP',
            'Reload_priv' => 'RELOAD',
            'Shutdown_priv' => 'SHUTDOWN',
            'Process_priv' => 'PROCESS',
            'File_priv' => 'FILE',
            'Grant_priv' => 'GRANT OPTION',
            'References_priv' => 'REFERENCES',
            'Index_priv' => 'INDEX',
            'Alter_priv' => 'ALTER',
            'Show_db_priv' => 'SHOW DATABASES',
            'Super_priv' => 'SUPER',
            'Create_tmp_table_priv' => 'CREATE TEMPORARY TABLES',
            'Lock_tables_priv' => 'LOCK TABLES',
            'Execute_priv' => 'EXECUTE',
            'Repl_slave_priv' => 'REPLICATION SLAVE',
            'Repl_client_priv' => 'REPLICATION CLIENT',
            'Create_view_priv' => 'CREATE VIEW',
            'Show_view_priv' => 'SHOW VIEW',
            'Create_routine_priv' => 'CREATE ROUTINE',
            'Alter_routine_priv' => 'ALTER ROUTINE',
            'Create_user_priv' => 'CREATE USER',
            'Event_priv' => 'EVENT',
            'Trigger_priv' => 'TRIGGER',
            'Create_tablespace_priv' => 'CREATE TABLESPACE',
            'Delete_history_priv' => 'DELETE HISTORY'
        ];

        foreach ($privilegeColumns as $column => $privilege) {
            if (isset($privileges[$column]) && $privileges[$column] == 'Y') {
                $sql = "GRANT $privilege ON *.* TO '$user'@'localhost'";
                DB::statement($sql);
            }
        }

        $sql = "FLUSH PRIVILEGES";
        DB::statement($sql);

        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error($e->getMessage());
        throw new \Exception('Error creating user');
    }

    return redirect()->back()->with('success', 'User created successfully');
}
    public function getUser($id)
    {
        $data = DB::table('mysql.user')->where('User', $id)->first();
        return ['data' => $data];
    }

    public function deleteUser($id)
    {
        try {
            DB::beginTransaction();
            $user = DB::table('mysql.user')->where('User', $id)->first();
            $sql = "DELETE FROM mysql.user WHERE User = '$user->User'";
            DB::statement($sql);
            $sql = "FLUSH PRIVILEGES";
            DB::statement($sql);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new \Exception('Error deleting user');
        }
    }

    public function updateUser($data, $id)
    {
        try {
            DB::beginTransaction();
            $user = DB::table('mysql.user')->where('User', $id)->first();
            $password = $data['password'] ?? null;
            if ($password) {
                $sql = "ALTER USER '$user->User'@'localhost' IDENTIFIED BY '$password'";
                DB::statement($sql);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new \Exception('Error updating user');
        }
    }

    public function getRoles()
    {
        $query = "SELECT Host, User FROM mysql.user";

        return DB::select($query);
    }

    public function assignRolesUser($data, $username)
    {
        try {
            DB::beginTransaction();
            Log::info('Iniciando asignación de roles', ['username' => $username]);

            $privileges = $data;
            Log::info('Datos recibidos', ['data' => $data]);
    
            // Revocar todos los privilegios antes de asignar nuevos
            $sql = "REVOKE ALL PRIVILEGES, GRANT OPTION FROM '$username'@'localhost'";
            DB::statement($sql);
            Log::info('Privilegios revocados', ['sql' => $sql]);
    
            // Asignar nuevos privilegios
            $privilegeColumns = [
                'Select_priv' => 'SELECT',
                'Insert_priv' => 'INSERT',
                'Update_priv' => 'UPDATE',
                'Delete_priv' => 'DELETE',
                'Create_priv' => 'CREATE',
                'Drop_priv' => 'DROP',
                'Reload_priv' => 'RELOAD',
                'Shutdown_priv' => 'SHUTDOWN',
                'Process_priv' => 'PROCESS',
                'File_priv' => 'FILE',
                'Grant_priv' => 'GRANT OPTION',
                'References_priv' => 'REFERENCES',
                'Index_priv' => 'INDEX',
                'Alter_priv' => 'ALTER',
                'Show_db_priv' => 'SHOW DATABASES',
                'Super_priv' => 'SUPER',
                'Create_tmp_table_priv' => 'CREATE TEMPORARY TABLES',
                'Lock_tables_priv' => 'LOCK TABLES',
                'Execute_priv' => 'EXECUTE',
                'Repl_slave_priv' => 'REPLICATION SLAVE',
                'Repl_client_priv' => 'REPLICATION CLIENT',
                'Create_view_priv' => 'CREATE VIEW',
                'Show_view_priv' => 'SHOW VIEW',
                'Create_routine_priv' => 'CREATE ROUTINE',
                'Alter_routine_priv' => 'ALTER ROUTINE',
                'Create_user_priv' => 'CREATE USER',
                'Event_priv' => 'EVENT',
                'Trigger_priv' => 'TRIGGER',
                'Create_tablespace_priv' => 'CREATE TABLESPACE',
                'Delete_history_priv' => 'DELETE HISTORY'
            ];
    
            foreach ($privilegeColumns as $column => $privilege) {
                if (isset($privileges[$column]) && $privileges[$column] == 'Y') {
                    $sql = "GRANT $privilege ON *.* TO '$username'@'localhost'";
                    DB::statement($sql);
                    Log::info('Privilegio asignado', ['sql' => $sql]);
                }
            }
    
            $sql = "FLUSH PRIVILEGES";
            DB::statement($sql);
            Log::info('Privilegios refrescados', ['sql' => $sql]);
    
            DB::commit();
            Log::info('Asignación de roles completada');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error asignando roles', ['exception' => $e]);
            throw new \Exception('Error assigning roles to user');
        }
    
        return redirect()->back()->with('success', 'Roles assigned successfully');
    }
    
    

    public function createRole($data)
    {
        try {
            DB::beginTransaction();
            $name = $data['role'];

            $sql = "CREATE ROLE $name";
            DB::statement($sql);

            $privileges = $data['privilege'];
            foreach ($privileges as $privilege) {
                $sql = "GRANT $privilege TO $name";
                DB::statement($sql);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    public function deleteRole($id)
    {
        try {
            DB::beginTransaction();
            $role = DB::table('mysql.roles')->where('role_id', $id)->first();
            $sql = "DROP ROLE $role->role";
            DB::statement($sql);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new \Exception('Error deleting role');
        }
    }
}

