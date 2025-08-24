<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'tb_proyecto';
    protected $primaryKey = 'ID_PROYECTO';
    public $timestamps = false;

    protected $fillable = [
        'TITULO_PROYECTO',
        'RESUMEN_PROYECTO',
        'FECHA_PROYECTO',
        'CONVOCATORIA_PROYECTO',
        'ID_ORGANISMO',
        'ID_AMBITO',
        'URL_IMAGEN_PROYECTO',
        'URL_PROYECTO',
        'ID_USUARIO'
    ];


    /**
     * Relación muchos a muchos con autores.
     */
    public function autores()
    {
        return $this->belongsToMany(Autor::class, 'tb_proyecto_autor', 'ID_PROYECTO', 'ID_AUTOR')
                    ->withPivot('ORDEN_AUTOR');
    }
}
