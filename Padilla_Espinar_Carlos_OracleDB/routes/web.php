<?php

use App\Http\Controllers\AuditController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Aplica el middleware a las rutas que requieren conexión a la base de datos
Route::middleware(['checkconnection'])->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    //RUTA PARA GESTIONAR ROLES Y USUARIOS DE BASE DE DATOS
    Route::get('users_db', [DashboardController::class, 'index_user'])->name('users_db');
    Route::get('users_db/{id}', [UserController::class, 'edit'])->name('users_db.edit');
    Route::get('roles/view_roles', [UserController::class, 'viewRoles'])->name('roles.viewRoles');
    Route::delete('roles/delete_role/{id}', [UserController::class, 'destroyRole'])->name('role.deleteRole');
    Route::post('users_db', [UserController::class, 'store'])->name('users_db.create');
    Route::delete('users_db/{id}', [UserController::class, 'destroy'])->name('users_db.delete');
    Route::put('users_db/{id}', [UserController::class, 'update'])->name('users_db.update');
    Route::put('users_db/assing_role/{id}', [UserController::class, 'assingRole'])->name('users_db.assignRole');
    Route::post('roles/create_role', [UserController::class, 'storeRole'])->name('roles.createRole');

    //RUTAS PARA LOS MODELOS
    Route::get('table/{name}', [DashboardController::class,'viewTable'])->name('table.show');
    Route::post('table/{name}', [DashboardController::class,'insertInToTable'])->name('table.insert');
    Route::post('table/{name}/{id}', [DashboardController::class,'updateInTable'])->name('item.update');
    Route::delete('table/delete/{name}/{id}', [DashboardController::class,'deleteInTable'])->name('item.destroy');

    Route::post('script', [DashboardController::class, 'runScript'])->name('execute');

    //RUTAS PARA BACKUPS
    Route::post('backup', [DashboardController::class, 'backup'])->name('backup');
    Route::post('restore', [DashboardController::class, 'restoreBackup'])->name('restore');
    //rutas para el punto 1
    Route::get('audit', [DashboardController::class, 'viewAudit'])->name('audit.view');

    Route::get('/generate-triggers-sql', [AuditController::class, 'generateTriggersSQL'])->name('generate-triggers-sql');
    Route::post('audit/filter', [DashboardController::class, 'filterAuditLogs'])->name('audit.filter');
    //RUTA PARA EL PUNTO 2
    Route::get('/update-appointments-status', [DashboardController::class, 'updateStatus'])->name('updateAppointmentsStatus');
    //Ruta para el punto 3
    Route::get('/execute-queries', [DashboardController::class, 'executeAndShowResults'])->name('execute-queries');
    Route::post('/execute-and-show-results', [DashboardController::class, 'executeAndShowResults'])->name('execute_hilo');
    //Ruta para el punto 4
    Route::get('pdf', [DashboardController::class, 'generatePdf'])->name('table.pdf');


});
