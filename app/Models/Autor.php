<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    use HasFactory;

    protected $table = 'tb_autor';
    protected $primaryKey = 'ID_AUTOR';
    public $timestamps = false;

    protected $fillable = [
        'NOMBRE_AUTOR',
        'APELLIDO_AUTOR'
    ];

    public function articulos()
    {
        return $this->belongsToMany(Articulo::class, 'tb_articulo_autor', 'ID_AUTOR', 'ID_ARTICULO')
                    ->withPivot('ORDEN_AUTOR');
    }
}
