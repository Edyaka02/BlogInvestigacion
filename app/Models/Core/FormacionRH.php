<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormacionRH extends Model
{
    use HasFactory;

    protected $table = 'tb_formacion_rh';
    protected $primaryKey = 'ID_RH';
    public $timestamps = false;

    protected $fillable = [
        'ID_FORMACION',
        'FECHA_RH',
        'HORAS_RH',
        'ID_AMBITO',
        'ID_NIVEL',
        'ID_ORGANISMO',
        'COMPROBANTE_RH',
        'ID_USUARIO'
    ];
}
