<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class TreamentType extends Model
{
    use HasFactory;

    protected $table = 'treatment_type';

    protected $primaryKey = 'treatment_type_id';

    protected $fillable = [
        'name',
        'description'
    ];

    public $timestamps = false;

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }
}
