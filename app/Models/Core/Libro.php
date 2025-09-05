<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;

    protected $table = 'tb_libro';
    protected $primaryKey = 'ID_LIBRO';
    public $timestamps = false;

    protected $fillable = [
        'TITULO_LIBRO',
        'CAPITULO_LIBRO',
        'ISBN_LIBRO',
        'FECHA_LIBRO',
        'EDITORIAL_LIBRO',
        'DOI_LIBRO',
        'URL_IMAGEN_LIBRO',
        'URL_LIBRO',
        'VISTA_LIBRO',
        'DESCARGA_LIBRO',
        'ID_USUARIO',
    ];

    public function autores()
    {
        return $this->belongsToMany(Autor::class, 'tb_libro_autor', 'ID_LIBRO', 'ID_AUTOR')
                    ->withPivot('ORDEN_AUTOR');
    }

}