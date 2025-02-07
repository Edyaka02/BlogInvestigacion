<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articulo;
use App\Models\Libro;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard($action = 'subir')
    {
        $tiposArticulos = config('tipos.articulos');

        return view('admin.dashboard', compact('tiposArticulos'));
    }

    // public function basurero()
    // {
    //     $articulosEliminados = Articulo::select('ID_ARTICULO', 'TITULO_ARTICULO')->where('ELIMINADO_ARTICULO', 1)->get();
    //     // $librosEliminados = Libro::onlyTrashed()->get();
    //     // $eventosEliminados = Evento::onlyTrashed()->get();

    //     return view('admin.admin_basurero', compact('articulosEliminados'));
    // }

    // public function basurero()
    // {
    //     $eliminados = DB::select("
    //         SELECT ID_ARTICULO as id, TITULO_ARTICULO as titulo, 'articulo' as tipo, ELIMINADO_ARTICULO as eliminado
    //         FROM tb_articulo
    //         WHERE ELIMINADO_ARTICULO = 1
    //         UNION
    //         SELECT ID_LIBRO as id, TITULO_LIBRO as titulo, 'libro' as tipo, ELIMINADO_LIBRO as eliminado
    //         FROM tb_libro
    //         WHERE ELIMINADO_LIBRO = 1
    //     ");

    //     return view('admin.admin_basurero', compact('eliminados'));
    // }

    // public function basurero(Request $request)
    // {
    //     $search = $request->input('search');
    //     $route = route('admin.basurero');

    //     $query = "
    //         SELECT ID_ARTICULO as id, TITULO_ARTICULO as titulo, 'articulo' as tipo, ELIMINADO_ARTICULO as eliminado
    //         FROM tb_articulo
    //         WHERE ELIMINADO_ARTICULO = 1
    //         UNION
    //         SELECT ID_LIBRO as id, TITULO_LIBRO as titulo, 'libro' as tipo, ELIMINADO_LIBRO as eliminado
    //         FROM tb_libro
    //         WHERE ELIMINADO_LIBRO = 1
    //     ";

    //     if ($search) {
    //         $query = "
    //             SELECT ID_ARTICULO as id, TITULO_ARTICULO as titulo, 'articulo' as tipo, ELIMINADO_ARTICULO as eliminado
    //             FROM tb_articulo
    //             WHERE ELIMINADO_ARTICULO = 1 AND TITULO_ARTICULO LIKE '%$search%'
    //             UNION
    //             SELECT ID_LIBRO as id, TITULO_LIBRO as titulo, 'libro' as tipo, ELIMINADO_LIBRO as eliminado
    //             FROM tb_libro
    //             WHERE ELIMINADO_LIBRO = 1 AND TITULO_LIBRO LIKE '%$search%'
    //         ";
    //     }

    //     $eliminados = DB::select($query);

    //     return view('admin.admin_basurero', compact('eliminados', 'search', 'route'));
    // }

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
