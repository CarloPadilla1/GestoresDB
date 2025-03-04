<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    use HasFactory;
    protected $table = 'auditoria';
    protected $primaryKey = 'ID';
    protected $fillable = [
            'NombreTabla',
            'FechaHora',
            'UsuarioActual',
            'DetalleAccion',
        ];
}
