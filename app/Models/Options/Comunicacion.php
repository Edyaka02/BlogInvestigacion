<?php

namespace App\Models\Options;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comunicacion extends Model
{
    use HasFactory;

    protected $table = 'tb_comunicacion';
    protected $primaryKey = 'ID_COMUNICACION';
    public $timestamps = false;
    
    protected $fillable = [
        'NOMBRE_COMUNICACION'
    ];
}
