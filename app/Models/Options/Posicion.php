<?php

namespace App\Models\Options;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posicion extends Model
{
    use HasFactory;

    protected $table = 'tb_posicion';
    protected $primaryKey = 'ID_POSICION';
    public $timestamps = false;
    
    protected $fillable = [
        'NOMBRE_POSICION'
    ];
}
