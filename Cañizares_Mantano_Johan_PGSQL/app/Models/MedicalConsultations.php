<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class MedicalConsultations extends Model
{
    use HasFactory;

    protected $table = 'medical_consultations';

    protected $primaryKey = 'consultation_id';

    protected $fillable = [
        'appointment_id',
        'consultation_date',
        'diagnosis',
        'treatment',
        'symptoms',
    ];

    public $timestamps = false;

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }
}
