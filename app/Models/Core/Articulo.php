<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Core\Autor;
use App\Models\Options\Tipo;
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
        'ID_TIPO',
        'URL_REVISTA_ARTICULO',
        'URL_ARTICULO',
        'URL_IMAGEN_ARTICULO',
        'ID_USUARIO',
    ];

    public function autores()
    {
        return $this->belongsToMany(Autor::class, 'tb_articulo_autor', 'ID_ARTICULO', 'ID_AUTOR')
            ->withPivot('ORDEN_AUTOR');
    }

    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'ID_TIPO', 'ID_TIPO');
    }
}
