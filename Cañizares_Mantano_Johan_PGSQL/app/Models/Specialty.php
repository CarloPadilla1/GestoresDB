<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Specialty extends Model
{
    use HasFactory;

    protected $table = 'specialty';
    protected $primaryKey = 'specialty_id';

    protected $fillable = [
        'name',
        'description'
    ];

    public $timestamps = false;

    public function doctors()
    {
        return $this->hasMany(MedicalStaff::class, 'specialty_id', 'specialty_id');
    }
    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }
}
