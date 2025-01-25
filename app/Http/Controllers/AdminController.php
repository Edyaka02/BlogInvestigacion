<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard($action = 'subir')
    {
        $tiposArticulos = config('tipos.articulos');

        return view('admin.dashboard', compact('tiposArticulos'));
    }
}
