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
        // Consulta para obtener los nombres de las tablas base en PostgreSQL
        $nameTables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_type = 'BASE TABLE' AND table_schema = 'public'");

        // Retorna la vista con los nombres de las tablas
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
        // Consulta para obtener los usuarios y sus roles en PostgreSQL
        $users = DB::select("
            SELECT
                u.usename AS username,
                u.usesysid AS user_id,
                CASE
                    WHEN r.rolcanlogin THEN 'Enabled'
                    ELSE 'Disabled'
                END AS account_status,
                NULL AS default_tablespace,
                NULL AS temporary_tablespace,
                r.rolcreatedb AS created,
                array_to_string(array_agg(r2.rolname), ',') || ',public' AS granted_roles
            FROM
                pg_catalog.pg_user u
            LEFT JOIN
                pg_catalog.pg_authid r ON u.usesysid = r.oid
            LEFT JOIN
                pg_catalog.pg_auth_members m ON u.usesysid = m.member
            LEFT JOIN
                pg_catalog.pg_roles r2 ON m.roleid = r2.oid
            GROUP BY
                u.usename,
                u.usesysid,
                r.rolcanlogin,
                r.rolcreatedb
            ORDER BY
                u.usename
        ");

        $users = array_map(function($user) {
            $user->granted_roles = $user->granted_roles ? explode(',', $user->granted_roles) : [];
            return $user;
        }, $users);

        // Consulta para obtener los roles en PostgreSQL
        $roles = DB::select("
            SELECT rolname AS role, oid AS role_id
            FROM pg_catalog.pg_roles
            ORDER BY rolname
        ");

        // Consulta para obtener los privilegios en PostgreSQL
        $privileges = DB::select("
            SELECT DISTINCT privilege_type AS privilege
            FROM information_schema.role_table_grants
            ORDER BY privilege_type
        ");

        // Consulta para obtener los tablespaces en PostgreSQL
        $tablespaces = DB::select("
            SELECT spcname AS tablespace_name
            FROM pg_catalog.pg_tablespace
            ORDER BY spcname
        ");

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
            dd($e->getMessage());
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

    public function runScript(Request $request)
    {
        $request->validate([
            'script' => 'nullable|string',
            'document_sql' => 'nullable|file',
        ]);

        $script = $request->input('script');
        $documentSql = $request->file('document_sql');


        if ($script === null && $documentSql === null) {
            return redirect()->route('dashboard')->withErrors(['script' => 'No script provided.']);
        }

        if ($documentSql !== null) {
            $script = file_get_contents($documentSql->getPathname());
        }

        $backupService = new BackupService();
        $result = $backupService->executeScript($script);

        if ($result['success'] === false) {
            return redirect()->route('dashboard')->withErrors(['script' => 'Unable to run script.', 'error' => $result['error']]);
        }

        return redirect()->route('dashboard')->with('success', 'Script executed successfully.');
    }


}
