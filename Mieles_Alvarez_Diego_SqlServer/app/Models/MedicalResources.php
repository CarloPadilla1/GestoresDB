<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class MedicalResources extends Model
{
    use HasFactory;

    protected $table = 'medical_resources';

    protected $primaryKey = 'resources_id';

    protected $fillable = [
        'resource_type',
        'description',
        'medical_department_id',
        'specialty_id'
    ];


    public function medicalDepartment()
    {
        return $this->belongsTo(MedicalDepartment::class, 'medical_department_id', 'medical_department_id');
    }

    public $timestamps = false;

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }
}
