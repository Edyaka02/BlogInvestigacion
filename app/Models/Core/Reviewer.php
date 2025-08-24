<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviewer extends Model
{
    use HasFactory;

    protected $table = 'tb_reviewer';
    protected $primaryKey = 'ID_REVIEWER';
    public $timestamps = false;

    protected $fillable = [
        'REVISTA_REVIEWER',
        'ARTICULO_REVIEWER',
        'FECHA_REVIEWER',
        'ID_AMBITO',
        'CERTIFICADO_REVIEWER',
        'URL_IMAGEN_REVIEWER',
        'ID_USUARIO'
    ];
}
