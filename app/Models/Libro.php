<?php

namespace App\Models;

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
        'YEAR_LIBRO',
        'EDITORIAL_LIBRO',
        'DOI_LIBRO',
        'URL_IMAGEN_LIBRO',
        'URL_LIBRO',
        'ELIMINADO_LIBRO'
    ];

    public function autores()
    {
        return $this->belongsToMany(Autor::class, 'tb_libro_autor', 'ID_LIBRO', 'ID_AUTOR')
                    ->withPivot('ORDEN_AUTOR');
    }
}