<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Premio extends Model
{
    use HasFactory;

    protected $table = 'tb_premio';
    protected $primaryKey = 'ID_PREMIO';
    public $timestamps = false;

    protected $fillable = [
        'ID_AMBITO',
        'ID_ORGANISMO',
        'PROYECTO_PREMIO',
        'FECHA_PREMIO',
        'RESUMEN_PREMIO',
        'CERTIFICADO_PREMIO',
        'URL_IMAGEN_PREMIO',
        'ID_USUARIO',
    ];

    /**
     * Relación muchos a muchos con autores.
     */
    public function autores()
    {
        return $this->belongsToMany(Autor::class, 'tb_premio_autor', 'ID_PREMIO', 'ID_AUTOR')
                    ->withPivot('ORDEN_AUTOR');
    }
}