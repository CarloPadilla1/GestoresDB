<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class MedicalHistory extends Model
{
    use HasFactory;

    protected $table = 'medical_history';

    protected $primaryKey = 'h_medical_id';

    protected $fillable = [
        'patient_id',
        'description',
    ];

    public $timestamps = false;

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }


    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }

}
