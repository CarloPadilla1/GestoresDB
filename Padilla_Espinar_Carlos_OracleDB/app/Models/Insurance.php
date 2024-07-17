<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Insurance extends Model
{
    use HasFactory;

    protected $table = 'insurance';

    protected $primaryKey = 'insurance_id';

    protected $fillable = [
        'company_id',
        'policy_number',
        'expiration',
        'patient_id'
    ];

    protected $casts = [
        'expiration' => 'datetime',
    ];


    public $timestamps = false;

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    public function company()
    {
        return $this->belongsTo(InsuranceCompany::class, 'company_id', 'company_id');
    }

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }

    public function getExpirationAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }
}
