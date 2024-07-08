<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';
    protected $primaryKey = 'patient_id';
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'gender',
        'phone_number',
        'address',
        'birthdate',
        'age'
    ];

    public $timestamps = false;

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id', 'patient_id');
    }

    public function familyBackground()
    {
        return $this->hasOne(FamilyBackground::class, 'patient_id', 'patient_id');
    }

    public function insurance()
    {
        return $this->hasMany(Insurance::class, 'patient_id', 'patient_id');
    }

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }
}
