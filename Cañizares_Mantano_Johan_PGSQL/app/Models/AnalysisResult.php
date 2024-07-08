<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AnalysisResult extends Model
{
    use HasFactory;

    protected $table = 'analysis_result';

    protected $primaryKey = 'analysis_result_id';

    protected $fillable = [
        'analysis_id',
        'result',
        'consultation_id',
        'appointment_id',
        'observations'
    ];

    public $timestamps = false;

    public function analysis()
    {
        return $this->belongsTo(Analysis_type::class, 'analysis_id', 'analysis_id');
    }

    public function consultation()
    {
        return $this->belongsTo(MedicalConsultations::class, 'consultation_id', 'consultation_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }


    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }
}
