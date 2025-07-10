<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Core\Autor;

class AutorController extends Controller
{
    public function index()
    {
        $autores = Autor::all();
        return view('autores.index', compact('autores'));
    }
}
