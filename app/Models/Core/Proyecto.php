<?php

namespace App\Models\Core;

use App\Models\Options\Organismo;
use App\Models\Options\Ambito;
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
        'VISTA_PROYECTO',
        'DESCARGA_PROYECTO',
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

    public function organismo()
    {
        return $this->belongsTo(Organismo::class, 'ID_ORGANISMO', 'ID_ORGANISMO');
    }

    public function ambito()
    {
        return $this->belongsTo(Ambito::class, 'ID_AMBITO', 'ID_AMBITO');
    }
}
