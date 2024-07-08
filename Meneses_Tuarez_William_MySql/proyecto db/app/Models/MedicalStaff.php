<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class MedicalStaff extends Model
{
    use HasFactory;

    protected $table = 'medical_staff';
    protected $primaryKey = 'medical_id';

    protected $fillable = [
        'name',
        'lastname',
        'email',
        'phone_number',
        'work_position',
        'medical_department_id',
        'specialty_id'
    ];

    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'specialty_id', 'specialty_id');
    }

    public $timestamps = false;

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }
    public function medical_department()
    {
        return $this->belongsTo(MedicalDepartment::class, 'medical_department_id', 'medical_department_id');
    }
}
