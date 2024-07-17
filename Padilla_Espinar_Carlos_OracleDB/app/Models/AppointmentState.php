<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AppointmentState extends Model
{
    use HasFactory;

    protected $table = 'appointment_state';

    protected $primaryKey = 'appointment_state_id';

    protected $fillable = [
        'state'
    ];

    public $timestamps = false;

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }
}
