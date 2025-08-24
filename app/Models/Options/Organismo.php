<?php

namespace App\Models\Options;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organismo extends Model
{
    use HasFactory;

    protected $table = 'tb_organismo';
    protected $primaryKey = 'ID_ORGANISMO';
    public $timestamps = false;

    protected $fillable = [
        'NOMBRE_ORGANISMO'
    ];
}
