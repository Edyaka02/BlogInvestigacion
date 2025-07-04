<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiembroRedes extends Model
{
    use HasFactory;

    protected $table = 'tb_miembro_redes';
    protected $primaryKey = 'ID_MIEMBRO';
    public $timestamps = false;

    protected $fillable = [
        'RED_MIEMBRO',
        'TEMATICA_MIEMBRO',
        'ID_AMBITO',
        'ID_PARTICIPACION',
        'FECHA_MIEMBRO',
        'ID_USUARIO'
    ];
}
