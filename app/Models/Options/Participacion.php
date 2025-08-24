<?php

namespace App\Models\Options;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participacion extends Model
{
    use HasFactory;

    protected $table = 'tb_participacion';
    protected $primaryKey = 'ID_PARTICIPACION';
    public $timestamps = false;

    protected $fillable = [
        'NOMBRE_PARTICIPACION'
    ];
}
