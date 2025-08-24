<?php

namespace App\Models\Options;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modalidad extends Model
{
    use HasFactory;

    protected $table = 'tb_modalidad';
    protected $primaryKey = 'ID_MODALIDAD';
    public $timestamps = false;

    protected $fillable = [
        'NOMBRE_MODALIDAD'
    ];
}
