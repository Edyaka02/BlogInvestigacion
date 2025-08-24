<?php

namespace App\Models\Options;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apoyo extends Model
{
    use HasFactory;
    protected $table = 'tb_apoyo';
    protected $primaryKey = 'ID_APOYO';
    public $timestamps = false;

    protected $fillable = [
        'NOMBRE_APOYO'
    ];
}
