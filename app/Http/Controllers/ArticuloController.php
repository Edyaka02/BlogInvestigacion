<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articulo;
use App\Models\Autor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Traits\Archivos;

class ArticuloController extends Controller
{
    use Archivos;

    // public function index()
    // {
    //     $articulos = Articulo::with('autores')
    //         ->select('ID_ARTICULO', 'TITULO_ARTICULO', 'FECHA_ARTICULO', 'REVISTA_ARTICULO', 'URL_IMAGEN_ARTICULO')
    //         ->get();

    //     return view('articulos.articulo', compact('articulos'));
    // }

    public function index()
    {
        // $articulos = Articulo::with('autores')
        // ->where('ELIMINADO_ARTICULO', false)
        // ->select('ID_ARTICULO', 'ISSN_ARTICULO', 'TITULO_ARTICULO', 'FECHA_ARTICULO', 'REVISTA_ARTICULO', 'URL_IMAGEN_ARTICULO')
        // ->paginate(6);
        $articulos = Articulo::with('autores')
        ->where('ELIMINADO_ARTICULO', false)
        ->select('ID_ARTICULO', 'ISSN_ARTICULO', 'TITULO_ARTICULO', 'FECHA_ARTICULO', 'REVISTA_ARTICULO', 'URL_IMAGEN_ARTICULO')
        ->orderBy('FECHA_ARTICULO', 'desc') // Ordenar por fecha más reciente
        ->paginate(6);

        // Procesar los nombres y apellidos de los autores
        foreach ($articulos as $articulo) {
            foreach ($articulo->autores as $autor) {
                $nombres = explode(' ', $autor->NOMBRE_AUTOR);
                $apellidos = explode(' ', $autor->APELLIDO_AUTOR);
                $autor->NOMBRE_AUTOR = $nombres[0];
                $autor->APELLIDO_AUTOR = $apellidos[0];
            }
        }

        return view('articulos.articulo', compact('articulos'));
    }

    public function adminIndex(Request $request)
    {
        $query = Articulo::with('autores')->where('ELIMINADO_ARTICULO', false);
        $hasResults = $this->applyFilters($query, $request);
        $years = $this->applyYears(2);

        $articulos = $query->paginate(10)->appends($request->except('page'));

        $tiposArticulos = config('tipos.articulos');

        $route = route('admin.articulos.index');

        return view('admin.admin_articulos', compact('articulos', 'tiposArticulos', 'years', 'route', 'hasResults'));
    }

    public function show($id)
    {
        $articulo = Articulo::with('autores')->findOrFail($id);

        // Generar URLs públicas
        $articulo->URL_ARTICULO = Storage::url($articulo->URL_ARTICULO);
        $articulo->URL_IMAGEN_ARTICULO = Storage::url($articulo->URL_IMAGEN_ARTICULO);

        return view('articulos.articulo_detalle', compact('articulo'));
    }

    public function create()
    {
        $tiposArticulos = config('tipos.articulos');
        $autores = Autor::all();
        return view('admin.modals.modal_articulo', compact('autores', 'tiposArticulos'));
    }

    public function store(Request $request)
    {
        // Verificar si el ISSN ya existe
        if (Articulo::where('ISSN_ARTICULO', $request->issn_articulo)->exists()) {
            return redirect()->route('admin.dashboard')->with('error', 'El ISSN ya existe.');
        }

        $request->validate([
            'issn_articulo' => 'required|string|max:9|unique:tb_articulo,ISSN_ARTICULO',
            'titulo_articulo' => 'required|string|max:255',
            'resumen_articulo' => 'required|string',
            'fecha_articulo' => 'required|date',
            'revista_articulo' => 'required|string|max:100',
            'tipo_articulo' => 'required|string|max:100',
            'url_revista_articulo' => 'required|url',
            'url_articulo' => 'nullable|file|mimes:pdf',
            'url_imagen_articulo' => 'nullable|file|mimes:png,jpg,jpeg,webp',
            'nombre_autores' => 'required|array',
            'apellido_autores' => 'required|array',
        ]);

        $articulo = new Articulo();
        $articulo->ISSN_ARTICULO = $request->issn_articulo;
        $articulo->TITULO_ARTICULO = $request->titulo_articulo;
        $articulo->RESUMEN_ARTICULO = $request->resumen_articulo;
        $articulo->FECHA_ARTICULO = $request->fecha_articulo;
        $articulo->REVISTA_ARTICULO = $request->revista_articulo;
        $articulo->TIPO_ARTICULO = $request->tipo_articulo;
        $articulo->URL_REVISTA_ARTICULO = $request->url_revista_articulo;
        $articulo->ELIMINADO_ARTICULO = false;

        // if ($request->hasFile('url_articulo')) {
        //     $file = $request->file('url_articulo');
        //     $fileName = $this->getUniqueFileName($file, 'articulos');
        //     $filePath = $file->storeAs('articulos', $fileName);
        //     $articulo->URL_ARTICULO = $filePath;
        // }

        // if ($request->hasFile('url_imagen_articulo')) {
        //     $file = $request->file('url_imagen_articulo');
        //     //$fileName = $this->getUniqueFileName($file, 'imagenes');
        //     $fileName = $this->getUniqueFileName($file, 'imagenes');
        //     $filePath = $file->storeAs('imagenes', $fileName);
        //     $articulo->URL_IMAGEN_ARTICULO = $filePath;
        // }

        // if ($request->hasFile('url_articulo')) {
        //     $file = $request->file('url_articulo');
        //     $fileName = $this->getUniqueFileName($file, 'articulos');
        //     $filePath = $file->move(public_path('articulos'), $fileName);
        //     $articulo->URL_ARTICULO = 'articulos/' . $fileName;
        // }

        // if ($request->hasFile('url_imagen_articulo')) {
        //     $file = $request->file('url_imagen_articulo');
        //     $fileName = $this->getUniqueFileName($file, 'imagenes');
        //     $filePath = $file->move(public_path('imagenes'), $fileName);
        //     $articulo->URL_IMAGEN_ARTICULO = 'imagenes/' . $fileName;
        // }

        if ($request->hasFile('url_articulo')) {
            $file = $request->file('url_articulo');
            $fileName = $this->getUniqueFileName($file, 'articulos');
            $filePath = $file->storeAs('public/articulos', $fileName);
            $articulo->URL_ARTICULO = 'storage/articulos/' . $fileName;
        }
    
        if ($request->hasFile('url_imagen_articulo')) {
            $file = $request->file('url_imagen_articulo');
            $fileName = $this->getUniqueFileName($file, 'imagenes');
            $filePath = $file->storeAs('public/imagenes', $fileName);
            $articulo->URL_IMAGEN_ARTICULO = 'storage/imagenes/' . $fileName;
        }

        $articulo->save();

        // Guardar autores
        $autores = [];
        foreach ($request->nombre_autores as $index => $nombre) {
            $autor = Autor::firstOrCreate([
                'NOMBRE_AUTOR' => $nombre,
                'APELLIDO_AUTOR' => $request->apellido_autores[$index]
            ]);
            $autores[$autor->ID_AUTOR] = ['ORDEN_AUTOR' => $index + 1];
        }
        $articulo->autores()->sync($autores);

        return redirect()->route('admin.dashboard')->with('success', 'Artículo registrado.');
    }

    public function update(Request $request, $id)
    {
        // Verificar que el ISSN no sea igual, siempre y cuando el ID no sea el mismo
        if (Articulo::where('ISSN_ARTICULO', $request->issn_articulo)->where('ID_ARTICULO', '!=', $id)->exists()) {
            return redirect()->route('admin.articulos.index')->with('error', 'El ISSN ya existe.');
        }

        $request->validate([
            'issn_articulo' => 'required|string|max:9|unique:tb_articulo,ISSN_ARTICULO,' . $id . ',ID_ARTICULO',
            'titulo_articulo' => 'required|string|max:255',
            'resumen_articulo' => 'required|string',
            'fecha_articulo' => 'required|date',
            'revista_articulo' => 'required|string|max:100',
            'tipo_articulo' => 'required|string|max:100',
            'url_revista_articulo' => 'nullable|url',
            'url_articulo' => 'nullable|file|mimes:pdf',
            'url_imagen_articulo' => 'nullable|file|mimes:png,jpg,jpeg,webp',
            'nombre_autores' => 'required|array',
            'apellido_autores' => 'required|array',
        ]);

        $articulo = Articulo::findOrFail($id);
        $articulo->ISSN_ARTICULO = $request->issn_articulo;
        $articulo->TITULO_ARTICULO = $request->titulo_articulo;
        $articulo->RESUMEN_ARTICULO = $request->resumen_articulo;
        $articulo->FECHA_ARTICULO = $request->fecha_articulo;
        $articulo->REVISTA_ARTICULO = $request->revista_articulo;
        $articulo->TIPO_ARTICULO = $request->tipo_articulo;
        $articulo->URL_REVISTA_ARTICULO = $request->url_revista_articulo;

        // if ($request->hasFile('url_articulo')) {
        //     $file = $request->file('url_articulo');
        //     $fileName = $this->getUniqueFileName($file, 'articulos');
        //     $filePath = $file->storeAs('articulos', $fileName);
        //     $articulo->URL_ARTICULO = $filePath;
        // }

        // if ($request->hasFile('url_imagen_articulo')) {
        //     $file = $request->file('url_imagen_articulo');
        //     $fileName = $this->getUniqueFileName($file, 'imagenes');
        //     $filePath = $file->storeAs('imagenes', $fileName);
        //     $articulo->URL_IMAGEN_ARTICULO = $filePath;
        // }

        // if ($request->hasFile('url_articulo')) {
        //     $file = $request->file('url_articulo');
        //     $fileName = $this->getUniqueFileName($file, 'articulos');
        //     $filePath = $file->move(public_path('articulos'), $fileName);
        //     $articulo->URL_ARTICULO = 'articulos/' . $fileName;
        // }

        // if ($request->hasFile('url_imagen_articulo')) {
        //     $file = $request->file('url_imagen_articulo');
        //     $fileName = $this->getUniqueFileName($file, 'imagenes');
        //     $filePath = $file->move(public_path('imagenes'), $fileName);
        //     $articulo->URL_IMAGEN_ARTICULO = 'imagenes/' . $fileName;
        // }

        if ($request->hasFile('url_articulo')) {
            $file = $request->file('url_articulo');
            $fileName = $this->getUniqueFileName($file, 'articulos');
            $filePath = $file->storeAs('public/articulos', $fileName);
            $articulo->URL_ARTICULO = 'articulos/' . $fileName;
        }
    
        if ($request->hasFile('url_imagen_articulo')) {
            $file = $request->file('url_imagen_articulo');
            $fileName = $this->getUniqueFileName($file, 'imagenes');
            $filePath = $file->storeAs('public/imagenes', $fileName);
            $articulo->URL_IMAGEN_ARTICULO = 'storage/imagenes/' . $fileName;
        }

        $articulo->save();

        // Guardar autores
        $autores = [];
        foreach ($request->nombre_autores as $index => $nombre) {
            $autor = Autor::firstOrCreate([
                'NOMBRE_AUTOR' => $nombre,
                'APELLIDO_AUTOR' => $request->apellido_autores[$index]
            ]);
            $autores[$autor->ID_AUTOR] = ['ORDEN_AUTOR' => $index + 1];
        }
        $articulo->autores()->sync($autores);

        return redirect()->route('admin.articulos.index')->with('success', 'Artículo actualizado.');
    }

    public function destroy($id)
    {
        $articulo = Articulo::findOrFail($id);

        // Realizar eliminación lógica
        $articulo->ELIMINADO_ARTICULO = true;
        $articulo->save();

        return redirect()->route('admin.articulos.index')->with('success', 'Artículo eliminado.');
    }

    public function restore($id)
    {
        $articulo = Articulo::findOrFail($id);

        // Restaurar el artículo si fue eliminado hace menos de una semana
        if ($articulo->ELIMINADO_ARTICULO && $articulo->updated_at->gt(now()->subWeek())) {
            $articulo->ELIMINADO_ARTICULO = false;
            $articulo->save();
            return redirect()->route('admin.articulos.index')->with('success', 'Artículo restaurado exitosamente.');
        }

        return redirect()->route('admin.articulos.index')->with('error', 'No se puede restaurar el artículo.');
    }

    public function forceDelete($id)
    {
        $articulo = Articulo::findOrFail($id);
        $articulo->delete();

        return redirect()->route('admin.basurero')->with('success', 'Artículo eliminado.');
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->has('search') && !is_null($request->input('search'))) {
            $search = $request->input('search');
            $searchTerms = explode(' ', $search); // Divide el término de búsqueda en palabras

            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->orWhere('TITULO_ARTICULO', 'like', "%$term%")
                        ->orWhere('ISSN_ARTICULO', 'like', "%$term%")
                        ->orWhere('REVISTA_ARTICULO', 'like', "%$term%")
                        ->orWhereHas('autores', function ($q) use ($term) {
                            $q->where('NOMBRE_AUTOR', 'like', "%$term%")
                                ->orWhere('APELLIDO_AUTOR', 'like', "%$term%");
                        });
                }
            });
        }

        if ($request->has('tipo') && !empty($request->input('tipo'))) {
            $query->whereIn('TIPO_ARTICULO', $request->input('tipo'));
        }

        if ($request->input('anio') === 'intervalo' && $request->has('anio_inicio') && $request->has('anio_fin') && !is_null($request->input('anio_inicio')) && !is_null($request->input('anio_fin'))) {
            $anio_inicio = $request->input('anio_inicio');
            $anio_fin = $request->input('anio_fin');

            // Validar que el año final no sea menor al año de inicio
            if ($anio_fin < $anio_inicio) {
                return redirect()->back()->with('error', 'El año final no puede ser menor que el año de inicio.');
            }

            $query->whereBetween(DB::raw('YEAR(FECHA_ARTICULO)'), [$anio_inicio, $anio_fin]);
        } elseif ($request->has('anio') && !empty($request->input('anio')) && $request->input('anio') !== 'todos') {
            $anios = $request->input('anio');
            if (!is_array($anios)) {
                $anios = [$anios];
            }
            $query->whereIn(DB::raw('YEAR(FECHA_ARTICULO)'), $anios);
        }

        $ordenar = $request->input('ordenar', 'fecha_desc'); // Establecer 'fecha_desc' como predeterminado
        switch ($ordenar) {
            case 'titulo_asc':
                $query->orderBy('TITULO_ARTICULO', 'asc');
                break;
            case 'titulo_desc':
                $query->orderBy('TITULO_ARTICULO', 'desc');
                break;
            case 'fecha_asc':
                $query->orderBy('FECHA_ARTICULO', 'asc');
                break;
            case 'fecha_desc':
                $query->orderBy('FECHA_ARTICULO', 'desc');
                break;
        }
        //return $years;
        return $query->exists();
    }

    private function applyYears($num)
    {
        return range(date('Y'), date('Y') - $num);
    }
}
