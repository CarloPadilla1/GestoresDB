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

            // Crear el login en el servidor
            $sql = "CREATE LOGIN $user WITH PASSWORD = '$password'";
            DB::statement($sql);

            // Crear el usuario en la base de datos actual
            $sql = "CREATE USER $user FOR LOGIN $user";
            DB::statement($sql);

            // Asignar roles y permisos
            if(!empty($data['role'])){
                $sql = "EXEC sp_addrolemember '$role', '$user'";
                DB::statement($sql);
            }

            // Permitir al usuario iniciar sesión
            $sql = "GRANT CONNECT TO $user";
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
    try {
        // Obtener el nombre de usuario
        $user = DB::table('sys.server_principals')->where('principal_id', $id)->first();

        if (!$user) {
            throw new \Exception('User not found');
        }

        // Obtener las bases de datos a las que el usuario tiene acceso
        $databases = DB::select('
            SELECT name
            FROM sys.databases
            WHERE HAS_DBACCESS(name) = 1
            ORDER BY name
        ');

        // Obtener permisos del usuario en cada base de datos
        $userPermissions = [];
        $allPermissions = DB::select('SELECT name AS object_name FROM sys.database_principals WHERE type = \'R\' ORDER BY name');
        foreach ($databases as $database) {
            // Cambiar el contexto a la base de datos actual
            DB::statement("USE [$database->name]");

            // Obtener los permisos del usuario en la base de datos actual
            $permissions = DB::select("
                SELECT dp.permission_name AS permission_name,
                        dp.state_desc AS permission_state,
                        dp.class_desc AS permission_class,
                        dp.major_id AS object_id,
                        so.name AS object_name
                    FROM sys.database_permissions dp
                LEFT JOIN sys.objects so ON dp.major_id = so.object_id
                WHERE dp.grantee_principal_id = (
                    SELECT principal_id
                    FROM sys.database_principals
                    WHERE name = ?
                )
                UNION
                SELECT 'ROLE MEMBER' AS permission_name,
                        '' AS permission_state,
                        'DATABASE_ROLE' AS permission_class,
                        rm.role_principal_id AS object_id,
                        r.name AS object_name
                    FROM sys.database_role_members rm
                INNER JOIN sys.database_principals u ON rm.member_principal_id = u.principal_id
                INNER JOIN sys.database_principals r ON rm.role_principal_id = r.principal_id
                WHERE u.name = ?
                ORDER BY permission_name", [$user->name, $user->name]);

            $userPermissions[$database->name] = $permissions;
        }
       
        return [
            'user' => $user,
            'databases' => $databases,
            'permissions' => $userPermissions,
            'all_permissions' => $allPermissions
        ];
    } catch (\Exception $e) {
        Log::error("Error fetching user details: " . $e->getMessage());
        throw new \Exception('Error fetching user details');
    }
}

    public function deleteUser($id)
    {
        try {
            DB::beginTransaction();
    
            // Obtener el usuario desde la base de datos
            $user = DB::table('sys.database_principals')->where('principal_id', $id)->first();
    
            if ($user) {
                // Eliminar el usuario
                $sql = "DROP USER [$user->name]";
                DB::statement($sql);
            } else {
                throw new \Exception('User not found');
            }
    
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

    public function updateRolesUserMap($data, $id)
    {
        try {
            DB::beginTransaction();

            $database = $data['database_name'];
            $user = $data['username'];
            $permissions = $data['permissions'];

            // Obtener todos los roles actuales del usuario en la base de datos específica
            $currentRoles = DB::select("
                SELECT dp.name AS role_name
                FROM
                    sys.database_role_members drm
                    JOIN sys.database_principals dp ON drm.role_principal_id = dp.principal_id
                WHERE drm.member_principal_id = USER_ID(?)
                AND dp.type = 'R'
                AND drm.role_principal_id IN (
                    SELECT principal_id FROM sys.database_principals WHERE type = 'R'
                )", [$user]);

            // Convertir los roles actuales a un array de strings
            $currentRolesArray = array_map(function($role) {
                return $role->role_name;
            }, $currentRoles);

            // Revocar cada uno de los roles actuales
            foreach ($currentRolesArray as $role) {
                Log::info("Revoking role $role from user $user in database $database");
                DB::statement("USE [$database]; ALTER ROLE [$role] DROP MEMBER [$user]");
            }

            // Asignar los nuevos roles
            foreach ($permissions as $role) {
                Log::info("Granting role $role to user $user in database $database");
                DB::statement("USE [$database]; ALTER ROLE [$role] ADD MEMBER [$user]");
            }

            DB::commit();
            Log::info("Roles assigned successfully to user $user in database $database");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new \Exception('Error assigning role');
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
                r.name AS RoleName,
                r.principal_id AS RoleId,
                STRING_AGG(p.permission_name, ', ') AS Privileges
            FROM 
                sys.database_principals r
            LEFT JOIN 
                sys.database_permissions p ON r.principal_id = p.grantee_principal_id
            WHERE 
                r.type = 'R'
                AND r.name <> 'public'
            GROUP BY 
                r.name, r.principal_id
            ORDER BY 
                r.name;
        ";
        //console log para querys
        //dd(DB::select($query));
        return DB::select($query);
    }

public function assignRolesUser($data, $id)
{
    try {
        DB::beginTransaction();

        // Obtener el nombre de usuario
        $user = DB::table('aplicaciones_web_2.sys.database_principals')
        ->where('principal_id', $id)
        ->first();

        // Verificar que el usuario existe
        if (!$user) {
            throw new \Exception('User not found');
        }

        // Obtener todos los roles actuales del usuario
        $currentRoles = DB::select('
            SELECT r.name AS role_name
            FROM aplicaciones_web_2.sys.database_role_members m
            JOIN aplicaciones_web_2.sys.database_principals r ON m.role_principal_id = r.principal_id
            WHERE m.member_principal_id = ?', [$user->principal_id]);

        // Revocar cada uno de los roles actuales
        foreach ($currentRoles as $role) {
            Log::info("Revoking role $role->role_name from user $user->name");
            DB::statement("ALTER ROLE [$role->role_name] DROP MEMBER [$user->name]");
        }

        // Asignar los nuevos roles en la base de datos
        if (is_array($data) && count($data) > 0) {
            foreach ($data as $role) {
                Log::info("Granting role $role to user $user->name");
                $sql = "ALTER ROLE [$role] ADD MEMBER [$user->name]";
                DB::statement($sql);
            }
        }

        DB::commit();
        Log::info("Roles assigned successfully to user $user->name");
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
    
            $name = $data['role'];
    
            // Crear el rol
            $sql = "CREATE ROLE \"$name\"";
            DB::statement($sql);
    
            // Asignar privilegios al rol
            $privileges = $data['privilege'];
            foreach ($privileges as $privilege) {
                $sql = "GRANT $privilege TO \"$name\"";
                DB::statement($sql);
            }
    
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new \Exception('Error creating role');
        }
    }
    

    public function deleteRole($id)
    {
        try {
            DB::beginTransaction();
    
            // Obtener el nombre del rol basado en el ID
            $role = DB::table('sys.database_principals')->where('principal_id', $id)->first();
    
            if (!$role) {
                throw new \Exception('Role not found');
            }
    
            // Crear el SQL para eliminar el rol
            $sql = "DROP ROLE [$role->name]";
    
            // Ejecutar la sentencia SQL
            DB::statement($sql);
    
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new \Exception('Error deleting role');
        }
    }

}
