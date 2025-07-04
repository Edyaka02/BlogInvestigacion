<?php

namespace App\Models\Options;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reconocimiento extends Model
{
    use HasFactory;

    protected $table = 'tb_reconocimiento';
    protected $primaryKey = 'ID_RECONOCIMIENTO';
    public $timestamps = false;

    protected $fillable = [
        'NOMBRE_RECONOCIMIENTO'
    ];
}
