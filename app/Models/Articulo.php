<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    use HasFactory;

    protected $table = 'tb_articulo';
    protected $primaryKey = 'ID_ARTICULO';
    public $timestamps = false;

    protected $fillable = [
        'ISSN_ARTICULO', 
        'TITULO_ARTICULO',
        'RESUMEN_ARTICULO',
        'FECHA_ARTICULO',
        'REVISTA_ARTICULO',
        'TIPO_ARTICULO',
        'URL_REVISTA_ARTICULO',
        'URL_ARTICULO',
        'URL_IMAGEN_ARTICULO',
        'ELIMINADO_ARTICULO'
    ];

    public function autores()
    {
        return $this->belongsToMany(Autor::class, 'tb_articulo_autor', 'ID_ARTICULO', 'ID_AUTOR')
                    ->withPivot('ORDEN_AUTOR');
    }
}
