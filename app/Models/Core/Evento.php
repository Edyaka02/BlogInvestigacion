<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'tb_evento';
    protected $primaryKey = 'ID_EVENTO';
    public $timestamps = false;

    protected $fillable = [
        'ID_TIPO',
        'TITULO_EVENTO',
        'RESUMEN_EVENTO',
        'NOMBRE_EVENTO',
        'ID_MODALIDAD',
        'ID_COMUNICACION',
        'ID_AMBITO',
        'EJE_TEMATICO_EVENTO',
        'URL_EVENTO',
        'URL_IMAGEN_EVENTO',
        'ID_USUARIO',
    ];

    public function autores()
    {
        return $this->belongsToMany(Autor::class, 'tb_evento_autor', 'ID_EVENTO', 'ID_AUTOR')
                    ->withPivot('ORDEN_AUTOR');
    }
}