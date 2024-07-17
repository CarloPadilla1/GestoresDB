<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use App\Services\ServiceModels;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

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

    public function filterAuditLogs(Request $request)
    {
        try {
            // Validar que se proporcionó el nombre de la tabla
            $request->validate([
                'table' => 'required|string',
            ]);

            $tableName = $request->input('table');
            $nameTables = DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema = ?', [Session::get('db_database')]);
            $data = DB::table('Auditoría')->where('table_name', $tableName)->get();
            // Obtener las columnas de la tabla de auditoría
            $columns = Schema::getColumnListing('Auditoría');

            // Obtener todos los nombres de las tablas en la base de datos actual
            $nameTables = DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema = ?', [Session::get('db_database')]);

            // Manejar caso en que no se encuentren registros de auditoría para la tabla seleccionada
            if ($data->isEmpty()) {
                return redirect()->route('audit.view')->withErrors(['dashboard.audit' => 'No hay auditorías para esta tabla.']);
            }

            // Retornar la vista 'dashboard.audit' con los datos, columnas y tablas disponibles
            return view('dashboard.audit', compact('data', 'columns', 'nameTables'));
        } catch (\Exception $e) {
            // Manejar errores y redirigir con mensajes de error
            return redirect()->route('audit.view')->withErrors(['audit' => 'No se pudo filtrar', 'error' => $e->getMessage()]);
        }
    }

    public function generatePdf(Request $request)
    {
        try {
            // Validar que el campo 'script' sea opcional y una cadena de texto
            $request->validate([
                'script' => 'nullable|string',
            ]);

            // Obtener el valor del campo 'script' del formulario
            $script = $request->input('script');

            // Si no se proporciona ningún script, redirigir de vuelta con un mensaje de error
            if ($script === null) {
                return redirect()->route('dashboard')->withErrors(['script' => 'No script provided.']);
            }

            // Obtener el valor del campo 'sql' del formulario
            $sql = $request->input('script');

            // Ejecutar la consulta SQL y obtener los resultados
            $results = DB::select($sql);

            // Generar el PDF usando la vista 'dashboard.pdf' y pasando los resultados
            $pdf = app(PDF::class);
            $pdf->loadView('dashboard.pdf', compact('results'));

            // Configurar el papel del PDF como A4 y orientación landscape
            $pdf->setPaper('A4', 'landscape');

            // Descargar el archivo PDF con el nombre 'query_result.pdf'
            return $pdf->download('reporte.pdf');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->withErrors(['script' => 'Unable to run script.', 'error' => $e->getMessage()]);
        }
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
