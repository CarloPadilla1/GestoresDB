<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class MedicalDepartment extends Model
{
    use HasFactory;

    protected $table = 'medical_department';
    protected $primaryKey = 'medical_department_id';

    protected $fillable = [
        'name',
        'description'
    ];

    public $timestamps = false;

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }
    public function medical_staff()
    {
        return $this->hasMany(MedicalStaff::class, 'medical_department_id', 'medical_department_id');
    }
}
