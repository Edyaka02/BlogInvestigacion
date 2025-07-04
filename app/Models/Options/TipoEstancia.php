<?php

namespace App\Models\Options;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEstancia extends Model
{
    use HasFactory;

    protected $table = 'tb_tipo_estancia';
    protected $primaryKey = 'ID_TIPO_ESTANCIA';
    public $timestamps = false;

    protected $fillable = [
        'NOMBRE_TIPO_ESTANCIA'
    ];
}
