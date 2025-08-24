<?php

namespace App\Models\Options;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ambito extends Model
{
    use HasFactory;

    protected $table = 'tb_ambito';
    protected $primaryKey = 'ID_AMBITO';
    public $timestamps = false;

    protected $fillable = [
        'NOMBRE_AMBITO'
    ];
}
