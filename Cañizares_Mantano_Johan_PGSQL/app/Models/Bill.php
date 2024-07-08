<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Bill extends Model
{
    use HasFactory;

    protected $table = 'bills';

    protected $primaryKey = 'bill_id';

    protected $fillable = [
        'patient_id',
        'appointment_id',
        'payment_type_id',
        'total',
        'description',
        'bill_date'
    ];

    public $timestamps = false;
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id', 'payment_type_id');
    }


    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }
}
