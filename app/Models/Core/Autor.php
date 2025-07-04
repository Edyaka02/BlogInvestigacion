<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    use HasFactory;

    protected $table = 'tb_autor';
    protected $primaryKey = 'ID_AUTOR';
    public $timestamps = false;

    protected $fillable = [
        'NOMBRE_AUTOR',
        'APELLIDO_AUTOR'
    ];

    /**
     * Relación genérica con cualquier modelo a través de una tabla pivote.
     */

    public function related($pivotTable, $foreignKey, $relatedKey)
    {
        return $this->belongsToMany(static::class, $pivotTable, $foreignKey, $relatedKey)
                    ->withPivot('ORDEN_AUTOR');
    }

    public static function syncAutores($model, array $autores, $relationMethod = 'autores')
    {
        if (method_exists($model, $relationMethod)) {
            $model->{$relationMethod}()->sync($autores);
        } else {
            throw new \Exception("La relación '{$relationMethod}' no existe en el modelo.");
        }
    }
}
