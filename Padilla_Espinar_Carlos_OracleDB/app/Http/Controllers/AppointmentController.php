<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AppointmentController extends Controller
{
    public function updateStatus()
    {
        try{
            DB::statement("BEGIN UpdateAppointmentsStatus; END;");
            return redirect()->back()->with('success', 'Appointment statuses updated successfully.');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Failed to update appointment statuses: ' . $e->getMessage());
        }
    }
}
