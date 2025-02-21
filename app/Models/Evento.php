<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'tb_evento';
    protected $primaryKey = 'ID_EVENTO';
    public $timestamps = false;

    protected $fillable = [
        'TIPO_EVENTO',
        'TITULO_EVENTO',
        'RESUMEN_EVENTO',
        'NOMBRE_EVENTO',
        'MODALIDAD_EVENTO',
        'COMUNICACION_EVENTO',
        'AMBITO_EVENTO',
        'EJE_TEMATICO_EVENTO',
        'URL_EVENTO',
        'URL_IMAGEN_EVENTO',
        'ELIMINADO_EVENTO',
    ];

    public function autores()
    {
        return $this->belongsToMany(Autor::class, 'tb_evento_autor', 'ID_EVENTO', 'ID_AUTOR')
                    ->withPivot('ORDEN_AUTOR');
    }
}