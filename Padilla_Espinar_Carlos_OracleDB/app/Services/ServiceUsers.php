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

            // Configurar la sesión de Oracle
            DB::statement('ALTER SESSION SET "_ORACLE_SCRIPT"=true');
            $user = $data['name'];
            $password = $data['password'];
            $tablespace = $data['tablespace'];
            $quota = $data['quota'];
            $role = $data['role'];
            // Crear el usuario
            $sql = "CREATE USER $user IDENTIFIED BY $password DEFAULT TABLESPACE $tablespace QUOTA $quota ON $tablespace";
            DB::statement($sql);

            // Asignar roles y permisos
            $sql = "GRANT $role TO $user";
            DB::statement($sql);

            $sql = "ALTER USER $user DEFAULT ROLE $role";
            DB::statement($sql);

            $sql = "GRANT CREATE SESSION TO $user";
            DB::statement($sql);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new \Exception('Error creating user');
        }
    }

    public function getUser($id)
    {
        $data = DB::table('DBA_USERS')->where('user_id', $id)->first();
        $tablespaces = DB::select('SELECT tablespace_name FROM dba_tablespaces');

        return [
            'data' => $data,
            'tablespaces' => $tablespaces
        ];
    }

    public function deleteUser($id)
    {
        try {
            DB::beginTransaction();
            DB::statement("ALTER SESSION SET \"_ORACLE_SCRIPT\" = true");
            $user = DB::table('DBA_USERS')->where('user_id', $id)->first();
            $sql = "DROP USER $user->username CASCADE";
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
            DB::statement("ALTER SESSION SET \"_ORACLE_SCRIPT\" = true");
            $user = DB::table('DBA_USERS')->where('user_id', $id)->first();

            $password = $data['password'] ?? null;
            $default_tablespace = $data['default_tablespace'];
            $account_status  = $data['account_status'];
            $temporary_tablespace = $data['temporary_tablespace'];

            if ($password) {
                $sql = "ALTER USER $user->username IDENTIFIED BY $password";
                DB::statement($sql);
            }

            DB::statement("ALTER USER $user->username DEFAULT TABLESPACE $default_tablespace");
            DB::statement("ALTER USER $user->username TEMPORARY TABLESPACE $temporary_tablespace");
            DB::statement("ALTER USER $user->username ACCOUNT ".($account_status == 'OPEN' ? 'UNLOCK' : 'LOCK'));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new \Exception('Error updating user');
        }
    }

    public function getRoles()
    {
        $query = "
            SELECT
                r.ROLE, r.ROLE_ID,
                XMLAGG(XMLELEMENT(e, p.PRIVILEGE, ', ') ORDER BY p.PRIVILEGE).EXTRACT('//text()').GETCLOBVAL() AS PRIVILEGES
            FROM
                DBA_SYS_PRIVS p
                JOIN DBA_ROLES r ON p.GRANTEE = r.ROLE
            GROUP BY
                r.ROLE, r.ROLE_ID
            ORDER BY
                r.ROLE
        ";

        return DB::select($query);
    }

    public function assignRolesUser($data, $id)
    {
        try {
            DB::beginTransaction();
            DB::statement("ALTER SESSION SET \"_ORACLE_SCRIPT\" = true");
            // Obtener el nombre de usuario
            $user = DB::table('DBA_USERS')->where('user_id', $id)->first();

            // Verificar que el usuario existe
            if (!$user) {
                throw new \Exception('User not found');
            }

            // Obtener todos los roles actuales del usuario
            $currentRoles = DB::select('SELECT granted_role FROM DBA_ROLE_PRIVS WHERE grantee = ?', [$user->username]);

            // Convertir los roles actuales a un array de strings
            $currentRolesArray = array_map(function($role) {
                return $role->granted_role;
            }, $currentRoles);

            // Revocar cada uno de los roles actuales
            foreach ($currentRolesArray as $role) {
                Log::info("Revoking role $role from user $user->username");
                DB::statement("REVOKE \"$role\" FROM $user->username");
            }

            // Asignar los nuevos roles
            if(is_array($data) && count($data) > 0){
                foreach ($data as $role) {
                    Log::info("Granting role $role to user $user->username");
                    $sql = "GRANT \"$role\" TO $user->username";
                    DB::statement($sql);
                }
            }

            DB::statement("ALTER USER $user->username DEFAULT ROLE ALL");
            DB::commit();
            Log::info("Roles assigned successfully to user $user->username");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error assigning roles to user $user->username: " . $e->getMessage());
            throw new \Exception('Error assigning role');
        }
    }

    public function createRole($data)
    {
        try {
            DB::beginTransaction();
            DB::statement("ALTER SESSION SET \"_ORACLE_SCRIPT\" = true");
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
            DB::statement("ALTER SESSION SET \"_ORACLE_SCRIPT\" = true");
            $role = DB::table('DBA_ROLES')->where('role_id', $id)->first();
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
