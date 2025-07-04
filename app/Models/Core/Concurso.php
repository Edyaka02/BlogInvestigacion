<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concurso extends Model
{
    use HasFactory;
    protected $table = 'tb_concurso';
    protected $primaryKey = 'ID_CONCURSO';
    public $timestamps = false;

    protected $fillable = [
        'CONVOCATORIA_CONCURSO',
        'ID_APOYO',
        'ID_POSICION',
        'ID_RECONOCIMIENTO',
        'PARTICIPANTES_CONCURSO',
        'PROYECTO_CONCURSO',
        'RESUMEN_CONCURSO',
        'FECHA_CONCURSO',
        'EJE_TEMATICO_CONCURSO',
        'ID_AMBITO',
        'CERTIFICADO_CONCURSO',
        'URL_IMAGEN_CONCURSO',
        'ID_USUARIO'
    ];
}
