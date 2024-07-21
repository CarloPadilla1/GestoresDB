<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use App\Services\ServiceModels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

use Barryvdh\DomPDF\PDF;
//
use App\Jobs\QueyExecutionJob;

class DashboardController extends Controller
{




    public function index()
    {
        $nameTables = DB::select('SELECT table_name FROM user_tables ORDER BY table_name');
        return view('dashboard.index', compact('nameTables'));
    }

    public function viewTable($name)
    {
        try {
            if ($name === 'AUDITORÍA') {
                return redirect()->route('audit.view');
            } else {
                $view = new ServiceModels($name);
                $view = $view->setModel();
                $data = $view['data'];
                $columns = $view['columns'];
                $dataForm = $view['dataForm'];
                $primaryKey = $view['primaryKey'];
                return view('dashboard.table', compact('data', 'columns', 'name', 'dataForm', 'primaryKey'));
            }
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->withErrors(['table' => 'Table not found.', 'error' => $e->getMessage()]);
        }
    }
    public function filterAuditLogs(Request $request)
    {
        try {
            // Validar que se proporcionó el nombre de la tabla
            $request->validate([
                'table' => 'required|string',
            ]);

            $tableName = $request->input('table');
            $data = DB::table('Auditoría')->where('nombretabla', $tableName)->get();
            // Obtener las columnas de la tabla de auditoría
            $columns = Schema::getColumnListing('Auditoría');

            // Obtener todos los nombres de las tablas en la base de datos actual
            $nameTables = DB::select('SELECT table_name FROM user_tables ORDER BY table_name');

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
        try{
            $users = DB::select("
                SELECT
                    u.username,
                    u.user_id,
                    u.account_status,
                    u.default_tablespace,
                    u.temporary_tablespace,
                    u.created,
                    LISTAGG(r.granted_role, ',') WITHIN GROUP (ORDER BY r.granted_role) AS granted_roles
                FROM
                    DBA_USERS u
                LEFT JOIN
                    DBA_ROLE_PRIVS r
                ON
                    u.username = r.grantee
                GROUP BY
                    u.username,
                    u.user_id,
                    u.account_status,
                    u.default_tablespace,
                    u.temporary_tablespace,
                    u.created
                ORDER BY
                    u.username
            ");
            $users = array_map(function($user) {
                    $user->granted_roles = $user->granted_roles ? explode(',', $user->granted_roles) : [];
                    return $user;
                }, $users);

            $roles = DB::select('SELECT * FROM dba_roles ORDER BY role');
            $privileges = DB::select('SELECT privilege FROM dba_sys_privs GROUP BY privilege ORDER BY privilege');
            $tablespaces = DB::select('SELECT tablespace_name FROM dba_tablespaces');

            return view('dashboard.admin_users.index', compact('users', 'roles','tablespaces', 'privileges'));

        }catch(\Exception $e){
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
    $oracleDataPumpDir = 'C:\app\21c\admin\XE\dpdump';
    $backupFileName = $backupFile->getClientOriginalName();
    $fullBackupFilePath = storage_path('app/' . $backupFilePath);
    rename($fullBackupFilePath, $oracleDataPumpDir . '/' . $backupFileName);

    $backupService = new BackupService();
    $result = $backupService->restoreBackup($password, $backupFileName);

    // if ($result['success'] === false) {
    //     return redirect()->route('dashboard')->withErrors(['backup' => 'Unable to restore backup.', 'error' => $result['error']]);
    // }

    return redirect()->route('dashboard')->with('success', 'Backup restored successfully.');
}


    public function runScript(Request $request)
    {
        $request->validate([
            'script' => 'nullable|string',
            'document_sql' => 'nullable|file',
        ]);
        if ($request->input('action') === 'pdf') {
            return $this->generatePdf($request);
        }
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
    //punto 1
    public function viewAudit()
    {
        try {
            $nameTables = DB::select('SELECT table_name FROM user_tables ORDER BY table_name');
            $data = DB::table('AUDITORÍA')->get();
            $columns = Schema::getColumnListing('AUDITORÍA');
            return view('dashboard.audit', compact('data', 'columns', 'nameTables'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->withErrors(['audit' => 'Audit log not found.', 'error' => $e->getMessage()]);
        }
    }



    //punto 2
    public function updateStatus()
    {
        try{
            DB::statement("BEGIN UpdateAppointmentsStatus; END;");
            return redirect()->back()->with('success', 'Appointment statuses updated successfully.');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Failed to update appointment statuses: ' . $e->getMessage());
        }
    }

    //punto 3
    public function hilos_vacio()
    {
        // Cuando se accede inicialmente a la vista, auditLogs estará vacío
        $auditLogs = collect();
        return view('dashboard.index', compact('auditLogs'));
    }

    public function executeAndShowResults(Request $request)
    {
        if ($request->isMethod('post')) {
            $selectedTables = $request->input('tables', []);

            foreach ($selectedTables as $table) {
                $query = "SELECT * FROM " . $table;
                QueyExecutionJob::dispatch($query);
            }

            sleep(5); // Por ejemplo, espera 5 segundos (ajusta según necesites)
        }
        $auditLogs = DB::select('SELECT * FROM query_executions ORDER BY id DESC FETCH FIRST 10 ROWS ONLY');
        $tables = DB::select("SELECT table_name FROM user_tables ORDER BY table_name");


        return view('query-result', compact('auditLogs', 'tables'));
    }
    //punto 4
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
}
