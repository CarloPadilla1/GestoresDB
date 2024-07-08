<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class VitalSigns extends Model
{
    use HasFactory;

    protected $table = 'vital_signs';

    protected $primaryKey = 'sign_id';

    protected $fillable = [
        'temperature',
        'weight',
        'height',
        'heart_rate',
        'breathing_frequency',
        'blood_pressure',
        'appointment_id',
        'consultation_id'
    ];

    public $timestamps = false;

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }

    public function consultation()
    {
        return $this->belongsTo(MedicalConsultations::class, 'consultation_id', 'consultation_id');
    }

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }
}
