<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Medical_pres_anal extends Model
{
    use HasFactory;
    protected $table = 'medical_prescription_analysis';
    protected $fillable = [
        'prescription_id',
        'analysis_result_id',
        'description'
    ];
    public $timestamps = false;
    public function prescription()
    {
        return $this->belongsTo(Prescription::class, 'prescription_id', 'prescription_id');
    }
    public function analysisResult()
    {
        return $this->belongsTo(AnalysisResult::class, 'analysis_result_id', 'analysis_result_id');
    }    
    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }
}
