<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estancia extends Model
{
    use HasFactory;

    protected $table = 'tb_estancia';
    protected $primaryKey = 'ID_ESTANCIA';
    public $timestamps = false;

    protected $fillable = [
        'PROYECTO_ESTANCIA',
        'ID_TIPO_ESTANCIA',
        'INSTITUCION_ESTANCIA',
        'ID_AMBITO',
        'FECHA_INICIO_ESTANCIA',
        'FECHA_FIN_ESTANCIA',
        'PRODUCTO_ESTANCIA',
        'URL_CARTA_ESTANCIA',
        'URL_CERTIFICADO_ESTANCIA',
        'URL_CONSTANCIA_ESTANCIA',
        'ID_USUARIO'
    ];
}
