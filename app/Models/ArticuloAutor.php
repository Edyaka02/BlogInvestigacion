<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ArticuloAutor extends Pivot
{
    protected $table = 'tb_articulo_autor';
    public $timestamps = false;
}
