<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distincion extends Model
{
    use HasFactory;

    protected $table = 'tb_distincion';
    protected $primaryKey = 'ID_DISTINCION';
    public $timestamps = false;

    protected $fillable = [
        'ID_ORGANISMO',
        'VIGENCIA_DISTINCION',
        'CERTIFICADO_DISTINCION',
        'URL_IMAGEN_DISTINCION',
        'ID_AMBITO',
        'ID_USUARIO'
    ];
}
