<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Auditoria extends Model
{
    use HasFactory;
    protected $table = 'auditoría';
    protected $primaryKey = 'ID';
    protected $fillable = [
            'NombreTabla',
            'FechaHora',
            'UsuarioActual',
            'DetalleAccion',
        ];

        public static function getTableColumns()
        {
            return Schema::getColumnListing((new static)->getTable());
        }
}
