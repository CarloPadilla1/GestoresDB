<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class PaymentType extends Model
{
    use HasFactory;

    protected $table = 'payment_type';

    protected $primaryKey = 'payment_type_id';

    protected $fillable = [
        'name',
        'description'
    ];

    public $timestamps = false;

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }

    public function bills()
    {
        return $this->hasMany(Bill::class, 'payment_type_id', 'payment_type_id');
    }

    public function getBillCountAttribute()
    {
        return $this->bills()->count();
    }
}
