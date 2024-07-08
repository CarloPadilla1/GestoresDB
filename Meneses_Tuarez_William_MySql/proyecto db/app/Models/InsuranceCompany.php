<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class InsuranceCompany extends Model
{
    use HasFactory;

    protected $table = 'insurance_company';

    protected $primaryKey = 'company_id';

    protected $fillable = [
        'name',
        'description'
    ];

    public $timestamps = false;

    public function insurance()
    {
        return $this->hasMany(Insurance::class, 'company_id', 'company_id');
    }

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }
}
