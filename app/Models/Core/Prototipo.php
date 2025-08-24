<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prototipo extends Model
{
    use HasFactory;

    protected $table = 'tb_prototipo';
    protected $primaryKey = 'ID_PROTOTIPO';
    public $timestamps = false;

    protected $fillable = [
        'NOMBRE_PROTOTIPO',
        'PROPOSITO_PROTOTIPO',
        'INSTITUCION_PROTOTIPO',
        'DESCRIPCION_PROTOTIPO',
        'OBJETIVO_PROTOTIPO',
        'CARACTERISTICAS_PROTOTIPO',
        'FECHA_PROTOTIPO',
        'URL_PROTOTIPO',
        'ID_USUARIO'
    ];

    /**
     * Relación muchos a muchos con autores.
     */
    public function autores()
    {
        return $this->belongsToMany(Autor::class, 'tb_prototipo_autor', 'ID_PROTOTIPO', 'ID_AUTOR')
                    ->withPivot('ORDEN_AUTOR');
    }
}
