<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    protected $primaryKey = 'appointment_id';

    protected $fillable = [
        'appointment_date',
        'appointment_time',
        'appointment_state_id',
        'patient_id',
        'doctor_id',
        'reason_for_appointment',
        'observations',
    ];

    public function appointment_state()
    {
        return $this->belongsTo(AppointmentState::class, 'appointment_state_id', 'appointment_state_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(MedicalStaff::class, 'doctor_id', 'medical_id');
    }

    public $timestamps = false;

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }
}
