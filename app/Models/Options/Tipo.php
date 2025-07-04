<?php

namespace App\Models\Options;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    use HasFactory;

    protected $table = 'tb_tipo';
    protected $primaryKey = 'ID_TIPO';
    public $timestamps = false;

    protected $fillable = [
        'NOMBRE_TIPO'
    ];
}
