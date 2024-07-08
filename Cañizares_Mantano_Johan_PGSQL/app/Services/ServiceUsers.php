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

            $user = $data['name'];
            $password = $data['password'];
            $role = $data['role'] ?? '';

            $sql = "CREATE USER $user WITH PASSWORD '$password'";
            DB::statement($sql);

            if ($role) {
                $sql = "GRANT $role TO $user";
                DB::statement($sql);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new \Exception('Error creating user');
        }
    }


    public function getUser($id)
{
    // Obtener el nombre del usuario
    $user = DB::table('pg_roles')->where('oid', $id)->first();

    // Verificar si el usuario existe
    if (!$user) {
        return response()->json(['error' => 'Usuario no encontrado'], 404);
    }

    // Obtener todas las bases de datos del sistema
    $databases = DB::connection('pgsql')
        ->select('SELECT datname FROM pg_database WHERE datistemplate = false;');

    // Privilegios a nivel de base de datos
    $databasePrivileges = ['CONNECT', 'CREATE', 'TEMPORARY'];

    // Privilegios a nivel de tabla
    $tablePrivileges = ['SELECT', 'INSERT', 'UPDATE', 'DELETE', 'TRUNCATE', 'REFERENCES', 'TRIGGER'];

    // Obtener permisos y roles del usuario en cada base de datos
    $userPermissions = [];
    foreach ($databases as $database) {
        $dbName = $database->datname;

        // Conectarse a la base de datos específica
        DB::connection('pgsql')->statement("SET search_path TO '$dbName'");

        $permissions = [];
        // Verificar privilegios a nivel de base de datos
        foreach ($databasePrivileges as $privilege) {
            $hasPrivilege = DB::connection('pgsql')->selectOne("
                SELECT has_database_privilege('$user->rolname', '$dbName', '$privilege') as has_privilege
            ");
            $permissions[] = [
                'privilege' => $privilege,
                'has_privilege' => $hasPrivilege->has_privilege
            ];
        }

        // Verificar privilegios a nivel de tabla
        foreach ($tablePrivileges as $privilege) {
            $hasPrivilege = DB::connection('pgsql')->selectOne("
                SELECT EXISTS (
                    SELECT 1
                    FROM information_schema.role_table_grants
                    WHERE grantee = '$user->rolname'
                    AND privilege_type = '$privilege'
                    AND table_catalog = '$dbName'
                ) as has_privilege
            ");
            $permissions[] = [
                'privilege' => $privilege,
                'has_privilege' => $hasPrivilege->has_privilege
            ];
        }

        $userPermissions[$dbName] = $permissions;
    }

    return [
        'user' => $user,
        'databases' => $databases,
        'userPermissions' => $userPermissions,
        'availablePrivileges' => array_merge($databasePrivileges, $tablePrivileges)
    ];
}


public function deleteUser($id)
{
    try {
        DB::beginTransaction();

        // Obtén el nombre de usuario usando el ID
        $user = DB::table('pg_shadow')->where('usesysid', $id)->first();

        if (!$user) {
            throw new \Exception("Usuario no encontrado.");
        }

        $username = $user->usename;

        // Revoque todos los privilegios otorgados al rol
        $revokePrivilegesSql = "
            DO \$\$
            DECLARE
                obj RECORD;
            BEGIN
                FOR obj IN
                    SELECT table_schema, table_name
                    FROM information_schema.table_privileges
                    WHERE grantee = '$username'
                LOOP
                    EXECUTE 'REVOKE ALL PRIVILEGES ON ' || quote_ident(obj.table_schema) || '.' || quote_ident(obj.table_name) || ' FROM $username';
                END LOOP;
            END
            \$\$;
        ";
        DB::statement($revokePrivilegesSql);

        // Reasigna la propiedad de los objetos
        $reassignOwnedSql = "REASSIGN OWNED BY \"$username\" TO postgres";
        DB::statement($reassignOwnedSql);

        // Luego, elimina la propiedad
        $dropOwnedSql = "DROP OWNED BY \"$username\"";
        DB::statement($dropOwnedSql);

        // Finalmente, elimina el rol
        $dropRoleSql = "DROP ROLE \"$username\" CASCADE";
        DB::statement($dropRoleSql);

        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error($e->getMessage());
    }
}



    public function updateUser($data, $id)
    {
        try {
            $user =  $data['username'];
            $password = $data['password'];
            DB::beginTransaction();

            if ($password) {
                $sql = "ALTER USER $user->name IDENTIFIED BY $password";
                DB::statement($sql);
            }


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new \Exception('Error updating user');
        }
    }

    public function updatePermissions($permissions, $id, $database)
{
    try {
        DB::beginTransaction();

        $user = DB::table('pg_roles')->where('oid', $id)->first();

        // Verificar que el usuario existe
        if (!$user) {
            throw new \Exception('User not found');
        }

        // Privilegios a nivel de base de datos
        $databasePrivileges = ['CONNECT', 'CREATE', 'TEMPORARY'];


        // Privilegios a nivel de tabla
        $tablePrivileges = ['SELECT', 'INSERT', 'UPDATE', 'DELETE', 'TRUNCATE', 'REFERENCES', 'TRIGGER'];

        // Revocar privilegios a nivel de base de datos
        foreach ($databasePrivileges as $privilege) {
            DB::statement("REVOKE $privilege ON DATABASE \"$database\" FROM \"$user->rolname\"");
        }


        // Revocar privilegios a nivel de esquema

            foreach ($tablePrivileges as $privilege) {
                if($privilege != 'CONNECT'){
                    DB::statement("REVOKE $privilege ON ALL TABLES IN SCHEMA public FROM \"$user->rolname\"");
                }
            }


        // Asignar los nuevos permisos
        if (is_array($permissions) && count($permissions) > 0) {
            foreach ($permissions as $permission) {
                if (in_array($permission, $databasePrivileges)) {
                    DB::statement("GRANT $permission ON DATABASE \"$database\" TO \"$user->rolname\"");
                }
                if(in_array($permission, $tablePrivileges)){
                    DB::statement("GRANT $permission ON ALL TABLES IN SCHEMA public TO \"$user->rolname\"");
                }
            }
        }
        DB::commit();
        Log::info("Permissions assigned successfully to user $user->rolname on database $database");
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error($e->getMessage());
        throw new \Exception('Error assigning permissions');
    }
}




    public function updateUserMap($data)
    {
        try {
            DB::beginTransaction();

            $user = $data['username'];
            $database = $data['database_name'];

            // Cambiar el contexto a la base de datos especificada
            DB::statement("USE [$database]");

            // Crear el usuario en la base de datos basado en el login
            $sqlCreateUser = "CREATE USER [$user] FOR LOGIN [$user]";
            Log::info("Creating user $user in database $database");
            DB::statement($sqlCreateUser);

            DB::commit();
            Log::info("User map created successfully for user $user in database $database");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new \Exception('Error updating user map');
        }
    }


    public function deleteUserMap($data)
    {
        try {
            DB::beginTransaction();

            $user = $data['username'];
            $database = $data['database_name'];

            // Cambiar el contexto a la base de datos especificada
            DB::statement("USE [$database]");

            // Eliminar el usuario de la base de datos
            $sqlDropUser = "DROP USER [$user]";
            Log::info("Dropping user $user from database $database");
            DB::statement($sqlDropUser);

            DB::commit();
            Log::info("User map deleted successfully for user $user in database $database");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new \Exception('Error deleting user map');
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

            // Obtener el nombre de usuario
            $user = DB::table('pg_roles')->where('oid', $id)->first();

            // Verificar que el usuario existe
            if (!$user) {
                throw new \Exception('User not found');
            }

            // Obtener todos los roles actuales del usuario
            $currentRoles = DB::select('
                SELECT r.rolname as role_name
                FROM
                    pg_auth_members m
                    JOIN pg_roles r ON m.roleid = r.oid
                WHERE m.member = ?', [$user->oid]);

            // Convertir los roles actuales a un array de strings
            $currentRolesArray = array_map(function($role) {
                return $role->role_name;
            }, $currentRoles);

            // Revocar cada uno de los roles actuales
            foreach ($currentRolesArray as $role) {
                Log::info("Revoking role $role from user $user->rolname");
                DB::statement("REVOKE \"$role\" FROM \"$user->rolname\"");
            }

            // Asignar los nuevos roles
            if (is_array($data) && count($data) > 0) {
                foreach ($data as $role) {
                    Log::info("Granting role $role to user $user->rolname");
                    $sql = "GRANT \"$role\" TO \"$user->rolname\"";
                    DB::statement($sql);
                }
            }

            DB::commit();
            Log::info("Roles assigned successfully to user $user->rolname");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
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

        DB::beginTransaction();

        // Get the role name using the id
        $role = DB::table('pg_roles')->where('oid', $id)->first();


        // Drop the role
        $sql = "DROP ROLE \"$role->rolname\"";
        DB::statement($sql);

        DB::commit();

    }

}
