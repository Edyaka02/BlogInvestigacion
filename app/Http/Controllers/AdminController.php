<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Core\Articulo;
use App\Models\Core\Libro;
use App\Models\Options\Tipo;
use App\Traits\OpcionesTrait;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    use OpcionesTrait;

    public function dashboard($action = 'subir')
    {
        $config = $this->getConfig();
        $tipos = Tipo::pluck('NOMBRE_TIPO', 'ID_TIPO'); 
        
        return view('admin.dashboard', compact('config', 'tipos'));
    }

    public function basurero(Request $request)
    {
        $search = $request->input('search');
        $route = route('admin.basurero');

        $articulosQuery = Articulo::select('ID_ARTICULO as id', 'TITULO_ARTICULO as titulo', DB::raw("'articulo' as tipo"))
            ->where('ELIMINADO_ARTICULO', 1);

        $librosQuery = Libro::select('ID_LIBRO as id', 'TITULO_LIBRO as titulo', DB::raw("'libro' as tipo"))
            ->where('ELIMINADO_LIBRO', 1);

        if ($search) {
            $articulosQuery->where('TITULO_ARTICULO', 'like', "%$search%");
            $librosQuery->where('TITULO_LIBRO', 'like', "%$search%");
        }

        $eliminados = $articulosQuery->union($librosQuery)->get();

        return view('admin.admin_basurero', compact('eliminados', 'search', 'route'));
    }

    public function forceDelete($id, $tipo)
    {
        if ($tipo == 'artículo') {
            $articulo = Articulo::findOrFail($id);
            $articulo->delete();
        } elseif ($tipo == 'libro') {
            $libro = Libro::findOrFail($id);
            $libro->delete();
        }

        return redirect()->route('admin.basurero')->with('success', ucfirst($tipo) . ' eliminado permanentemente.');
    }

}
