<?php

namespace App\Models\Options;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoFormacion extends Model
{
    use HasFactory;

    protected $table = 'tb_tipo_formacion';
    protected $primaryKey = 'ID_FORMACION';
    public $timestamps = false;

    protected $fillable = [
        'NOMBRE_FORMACION'
    ];
}
