<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Analysis_type extends Model
{
    use HasFactory;

    protected $table = 'analysis_type';

    protected $primaryKey = 'analysis_id';

    protected $fillable = [
        'analysis_type',
        'description'
    ];

    public $timestamps = false;

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }

    public function analysisResult()
    {
        return $this->hasMany(AnalysisResult::class, 'analysis_id', 'analysis_id');
    }

}
