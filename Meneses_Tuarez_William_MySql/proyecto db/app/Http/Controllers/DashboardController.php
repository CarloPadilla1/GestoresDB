<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use App\Services\ServiceModels;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Barryvdh\DomPDF\PDF;

class DashboardController extends Controller
{
    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    public function index()
    {
        $nameTables = DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema = ?', [Session::get('db_database')]);
        return view('dashboard.index', compact('nameTables'));
    }

    public function viewTable($name)
    {
        try {
            if ($name === 'auditoría') {
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
    public function viewAudit()
    {
        try {
            $nameTables = DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema = ?', [Session::get('db_database')]);
            $data = DB::table('Auditoría')->get();
            $columns = Schema::getColumnListing('Auditoría');
            return view('dashboard.audit', compact('data', 'columns', 'nameTables'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->withErrors(['audit' => 'Audit log not found.', 'error' => $e->getMessage()]);
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
        // Obtén la información de los usuarios y sus roles
        $users = DB::select("
            SELECT
            u.User as username,
            u.User as user_id,
            u.Host as account_status,
            'default' as default_tablespace,
            'temporary' as temporary_tablespace,
            GROUP_CONCAT(r.Role ORDER BY r.Role) AS granted_roles
            FROM
            mysql.user u
            LEFT JOIN
            mysql.roles_mapping r
            ON
            u.User = r.User
            GROUP BY
            u.User,
            u.Host
            ORDER BY
            u.User
        ");

        $users = array_map(function($user) {
            $user->granted_roles = $user->granted_roles ? explode(',', $user->granted_roles) : [];
            return $user;
        }, $users);

        return view('dashboard.admin_users.index', compact('users'));

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
        $request->validate([
            'password' => 'required'
        ]);

        $password = $request->input('password');

        $result = $this->backupService->backup($password);

        if ($result['success'] === false) {
            return redirect()->route('dashboard')->withErrors(['backup' => 'Unable to create backup.', 'error' => $result['error']]);
        }

        return redirect()->route('dashboard')->with('success', 'Backup created successfully.');
    }

    public function restoreBackup(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'backupFile' => 'required|file'
        ]);

        $password = $request->input('password');
        $backupFile = $request->file('backupFile')->getClientOriginalName();
        
        // Mover el archivo al directorio de backups
        $request->file('backupFile')->storeAs('backups', $backupFile);

        $result = $this->backupService->restoreBackup($password, $backupFile);

        if ($result['success'] === false) {
            return redirect()->route('dashboard')->withErrors(['backup' => 'Unable to restore backup.', 'error' => $result['error']]);
        }

        return redirect()->route('dashboard')->with('success', 'Backup restored successfully.');
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
        if ($request->input('action') === 'pdf') {
            return $this->generatePdf($request);
        }
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

        if ($result['success'] === true) {
            return redirect()->route('dashboard')->withErrors(['script' => 'Unable to run script.', 'error' => $result['error']]);
        }

        return redirect()->route('dashboard')->with('success', 'Script executed successfully.');
    }

}
