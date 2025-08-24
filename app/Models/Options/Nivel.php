<?php

namespace App\Models\Options;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    use HasFactory;

    protected $table = 'tb_nivel';
    protected $primaryKey = 'ID_NIVEL';
    public $timestamps = false;

    protected $fillable = [
        'NOMBRE_NIVEL'
    ];
}
