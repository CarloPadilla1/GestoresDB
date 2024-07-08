<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class FamilyBackground extends Model
{
    use HasFactory;

    protected $table = 'family_background';

    protected $primaryKey = 'background_id';

    protected $fillable =  [
        'patient_id',
        'name',
        'description'];

    public function patient(){
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    public $timestamps = false;

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }


}
