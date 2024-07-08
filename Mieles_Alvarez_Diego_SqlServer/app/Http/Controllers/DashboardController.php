<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use App\Services\ServiceModels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{




    public function index()
    {
        $nameTables = DB::select('SELECT table_name FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = \'BASE TABLE\'');
        return view('dashboard.index', compact('nameTables'));
    }
    public function viewTable($name)
    {
        try{
            $view = new ServiceModels($name);
            $view = $view->setModel();
            $data = $view['data'];
            $columns = $view['columns'];
            $dataForm = $view['dataForm'];
            $primaryKey = $view['primaryKey'];
            return view('dashboard.table', compact('data', 'columns', 'name', 'dataForm', 'primaryKey'));
        }catch(\Exception $e){
            return redirect()->route('dashboard')->withErrors(['table' => 'Table not found.', 'error' => $e->getMessage()]);
        }
    }

    public function insertInToTable(Request $request, $name)
    {
        try{
            $data = $request->all();
            $insert = new ServiceModels($name);
            $insert->insertData($data);
            return redirect()->route('table.show', $name)->with('success', 'Data inserted successfully.');
        }catch(\Exception $e){
            return redirect()->route('table.show', $name)->withErrors(['insert' => 'Unable to insert data into the table.', 'error' => $e->getMessage()]);
        }
    }

    public function updateTable(Request $request, $name)
    {
        try{
            return redirect()->route('table.show', $name)->with('success', 'Data updated successfully.');
        }catch(\Exception $e){
            return redirect()->route('table.show', $name)->withErrors(['update' => 'Unable to update data in the table.', 'error' => $e->getMessage()]);
        }
    }

    public function index_user()
{
    try {
        // Consulta para obtener los usuarios y sus roles en SQL Server
        $users = DB::select("
            SELECT
                dp.name AS username,
                dp.principal_id AS user_id,
                CASE
                    WHEN sl.is_disabled = 1 THEN 'Disabled'
                    ELSE 'Enabled'
                END AS account_status,
                dp.default_schema_name AS default_schema,
                dp.create_date AS created,
                STRING_AGG(rp.name, ', ') + ', public' AS granted_roles
            FROM
                sys.database_principals dp
            LEFT JOIN
                sys.sql_logins sl ON dp.sid = sl.sid
            LEFT JOIN
                sys.database_role_members drm ON dp.principal_id = drm.member_principal_id
            LEFT JOIN
                sys.database_principals rp ON drm.role_principal_id = rp.principal_id
            WHERE
                dp.type IN ('S', 'U')
            GROUP BY
                dp.name,
                dp.principal_id,
                sl.is_disabled,
                dp.default_schema_name,
                dp.create_date,
                dp.type
            ORDER BY
                dp.name;
        ");

        $users = array_map(function($user) {
            $user->granted_roles = $user->granted_roles ? explode(',', $user->granted_roles) : [];
            return $user;
        }, $users);

        // Consulta para obtener los roles en SQL Server
        $roles = DB::select(
            "
                SELECT name AS role, principal_id AS role_id
                FROM sys.database_principals
                WHERE type = 'R'
                ORDER BY name
            "
        );


        // Consulta para obtener los privilegios en SQL Server
        $privileges = DB::select('
            SELECT DISTINCT permission_name AS privilege
            FROM sys.database_permissions
            ORDER BY permission_name
        ');



        // Consulta para obtener los tablespaces en SQL Server (equivalente son los filegroups)
        $tablespaces = DB::select('SELECT name AS tablespace_name FROM sys.filegroups ORDER BY name');

        return view('dashboard.admin_users.index', compact('users', 'roles', 'tablespaces', 'privileges'));

    } catch (\Exception $e) {
        return redirect()->route('dashboard')->withErrors(['table' => 'Table not found.', 'error' => $e->getMessage()]);
    }
}


    public function deleteInTable($name, $id)
    {
        try{
            $delete = new ServiceModels($name);
            $delete->deleteData($id);
            return redirect()->route('table.show', $name)->with('success', 'Data deleted successfully.');
        }catch(\Exception $e){
            // dd($e->getMessage());
            return redirect()->route('table.show', $name)->withErrors(['delete' => 'Unable to delete data from the table.', 'error' => $e->getMessage()]);
        }
    }

    public function updateInTable(Request $request, $name, $id)
    {
        try{
            $data = $request->all();
            $update = new ServiceModels($name);
            $update->updateData($id, $data);
            return redirect()->route('table.show', $name)->with('success', 'Data updated successfully.');
        }catch(\Exception $e){
            return redirect()->route('table.show', $name)->withErrors(['update' => 'Unable to update data in the table.', 'error' => $e->getMessage()]);
        }
    }

    public function backup(Request $request)
    {
        try{
            $request->validate([
                'password' => 'required',
                'backupType' => 'required'
            ]);
            $backup = new BackupService();
            $backup = $backup->backup($request->password, $request->backupType);
            if($backup['success'] == false){
                return redirect()->route('dashboard')->withErrors(['backup' => 'Unable to create backup.', 'error' => $backup['error']]);
            }
            return redirect()->route('dashboard')->with('success', 'Backup created successfully.');
        }catch(\Exception $e){
            return redirect()->route('dashboard')->withErrors(['backup' => 'Unable to create backup.', 'error' => $e->getMessage()]);
        }
    }

    public function restoreBackup(Request $request)
{
    $request->validate([
        'password' => 'required',
        'backupFile' => 'required|file'
    ]);

    $password = $request->input('password');
    $backupFile = $request->file('backupFile');

    // Almacenar temporalmente el archivo en una ubicación accesible
    $backupFilePath = $backupFile->storeAs('', $backupFile->getClientOriginalName());

    // Mover el archivo al directorio esperado por Oracle (DATA_PUMP_DIR)

    $backupFileName = $backupFile->getClientOriginalName();



    $backupService = new BackupService();
    $result = $backupService->restoreBackup($password, $backupFileName);

    if ($result['success'] === false) {
        return redirect()->route('dashboard')->withErrors(['backup' => 'Unable to restore backup.', 'error' => $result['error']]);
    }

    return redirect()->route('dashboard')->with('success', 'Backup restored successfully.');
}

}
