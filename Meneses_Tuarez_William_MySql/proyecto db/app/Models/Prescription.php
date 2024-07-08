<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Prescription extends Model
{
    use HasFactory;

    protected $table = 'prescription';

    protected $primaryKey = 'prescription_id';

    protected $fillable = [
        'treatment_type_id',
        'consultation_id',
        'appointment_id',
        'medicine',
        'dose',
        'instructions',
    ];

    public $timestamps = false;

    public function treatmentType()
    {
        return $this->belongsTo(TreamentType::class, 'treatment_type_id', 'treatment_type_id');
    }

    public function consultation()
    {
        return $this->belongsTo(MedicalConsultations::class, 'consultation_id', 'consultation_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }

    public function analysisResult()
    {
        return $this->belongsToMany(AnalysisResult::class, 'medical_prescription_analysis', 'prescription_id', 'analysis_result_id');
    }

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }
    public function medical_prescription_analysis()
    {
        return $this->hasMany(Medical_pres_anal::class, 'prescription_id', 'prescription_id');
    }
    public function medical_department()
    {
        return $this->belongsTo(MedicalDepartment::class, 'medical_department_id', 'medical_department_id');
    }
}
