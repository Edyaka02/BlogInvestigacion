<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Core\Articulo;
use App\Models\Core\Autor;
use App\Models\Options\Tipo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Traits\Archivos;
use App\Traits\AutorTrait;
use App\Traits\YearTrait;
use App\Traits\OpcionesTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;



class ArticuloController extends Controller
{
    use Archivos, AutorTrait, YearTrait, OpcionesTrait;

    public function index(Request $request)
    {
        $query = Articulo::with('autores')
            // ->where('ELIMINADO_ARTICULO', false)
            ->select('ID_ARTICULO', 'ISSN_ARTICULO', 'TITULO_ARTICULO', 'FECHA_ARTICULO', 'REVISTA_ARTICULO', 'URL_IMAGEN_ARTICULO');

        $hasResults = $this->applyFilters($query, $request);
        $years = $this->applyYears(2);

        $tiposArticulos = Tipo::pluck('NOMBRE_TIPO', 'ID_TIPO');

        $articulos = $query->paginate(1)->appends($request->except('page'));

        // $config = $this->getConfig(['tiposArticulos']);

        $route = route('articulos.articulo');

        // Procesar los nombres y apellidos de los autores
        foreach ($articulos as $articulo) {
            foreach ($articulo->autores as $autor) {
                $this->splitAuthorName($autor);
            }
            $articulo->URL_IMAGEN_ARTICULO = Storage::url($articulo->URL_IMAGEN_ARTICULO);
        }

        // return view('articulos.articulo', compact('articulos', 'tiposArticulos', 'years', 'route', 'hasResults'));
        return view('entities.articulos.index', compact('articulos', 'tiposArticulos', 'years', 'route', 'hasResults'));
    }

    public function adminIndex(Request $request)
    {
        // $query = Articulo::with(['autores', 'tipo']);
        $query = Articulo::select('tb_articulo.*')
            ->distinct()
            ->with(['autores', 'tipo']);

        // $hasResults = $this->applyFilters($query, $request);
        $this->applyFilters($query, $request);

        $articulos = $query->paginate(2)->appends($request->except('page'));

        // Procesar URLs de imágenes para las peticiones AJAX
        if ($request->ajax()) {
            foreach ($articulos as $articulo) {
                if ($articulo->URL_IMAGEN_ARTICULO) {
                    $articulo->URL_IMAGEN_ARTICULO = Storage::url($articulo->URL_IMAGEN_ARTICULO);
                }
            }

            return response()->json([
                'articulos' => $articulos,
                // 'hasResults' => $hasResults
            ]);
        }

        // Para la primera carga (no AJAX), solo devolver la vista
        $tipos = Tipo::pluck('NOMBRE_TIPO', 'ID_TIPO');
        $years = $this->applyYears(2);

        return view('entities.articulos.edit', compact('years', 'tipos'));
    }

    // public function adminFiltrar(Request $request)
    // {
    //     // $query = Articulo::with('autores');
    //     $query = Articulo::with(['autores', 'tipo']);

    //     $hasResults = $this->applyFilters($query, $request);

    //     $years = $this->applyYears(2);

    //     $tiposArticulos = Tipo::pluck('NOMBRE_TIPO', 'ID_TIPO');

    //     $articulos = $query->paginate(30)->appends($request->except('page'));

    //     $route = route('admin.articulos.index');

    //     // Si es una petición AJAX, devolver JSON

    //     return response()->json([
    //         'articulos' => $articulos,
    //         'tiposArticulos' => $tiposArticulos,
    //         'years' => $years,
    //         'hasResults' => $hasResults
    //     ]);

    //     // Si es una petición AJAX, devolver JSON
    //     // if ($request->ajax()) {
    //     //     return response()->json([
    //     //         'articulos' => $articulos,
    //     //         'tiposArticulos' => $tiposArticulos,
    //     //         'years' => $years,
    //     //         'hasResults' => $hasResults
    //     //     ]);
    //     // }

    //     // return view('entities.articulos.edit', compact('articulos', 'tiposArticulos', 'years', 'route', 'hasResults'));
    // }

    public function show($id)
    {
        $articulo = Articulo::with('autores')->findOrFail($id);

        // Generar URLs públicas
        $articulo->URL_ARTICULO = Storage::url($articulo->URL_ARTICULO);
        $articulo->URL_IMAGEN_ARTICULO = Storage::url($articulo->URL_IMAGEN_ARTICULO);

        return view('entities.articulos.show', compact('articulo'));
    }


    // ✅ VERIFICAR: ArticuloController.php
    public function store(Request $request)
    {
        // Verificar si el ISSN ya existe
        if (Articulo::where('ISSN_ARTICULO', $request->issn_articulo)->exists()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El ISSN ya existe.'
                ], 422);
            }
            return redirect()->route('entities.dashboard.dashboard')->with('error', 'El ISSN ya existe.');
        }

        try {
            $this->validateArticulo($request);

            $articulo = new Articulo();
            $this->assignArticuloData($articulo, $request);
            $articulo->save();

            // Guardar autores
            $autores = $this->handleAutores($request);
            $articulo->autores()->sync($autores);

            // ✅ IMPORTANTE: Solo respuesta JSON para AJAX
            if ($request->ajax()) {
                $response = [
                    'success' => true,
                    'message' => 'Artículo creado correctamente.',
                    'articulo' => $articulo->load(['autores', 'tipo'])
                ];

                return response()->json($response);
            }

            return redirect()->route('entities.dashboard.dashboard')->with('success', 'Artículo registrado.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el artículo: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('entities.dashboard.dashboard')->with('error', 'Error al crear el artículo.');
        }
    }

    public function update(Request $request, $id)
    {
        // Debug: ver qué datos llegan
        Log::info('Datos recibidos en update:', $request->all());

        // Verificar que el ISSN no sea igual, siempre y cuando el ID no sea el mismo
        if (Articulo::where('ISSN_ARTICULO', $request->issn_articulo)->where('ID_ARTICULO', '!=', $id)->exists()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El ISSN ya existe.'
                ], 422);
            }
            return redirect()->route('admin.articulos.index')->with('error', 'El ISSN ya existe.');
        }

        try {
            $this->validateArticulo($request, $id);

            $articulo = Articulo::findOrFail($id);

            // Debug: ver artículo antes de actualizar
            Log::info('Artículo antes de actualizar:', $articulo->toArray());

            $this->assignArticuloData($articulo, $request);

            // Debug: ver artículo después de asignar datos
            Log::info('Artículo después de asignar datos:', $articulo->toArray());

            $articulo->save();

            // Debug: confirmar que se guardó
            Log::info('Artículo después de guardar:', $articulo->fresh()->toArray());

            // Guardar autores
            $autores = $this->handleAutores($request);
            $articulo->autores()->sync($autores);

            // Si es una petición AJAX, devolver JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Artículo actualizado.',
                    'articulo' => $articulo->load(['autores', 'tipo'])
                ]);
            }

            return redirect()->route('admin.articulos.index')->with('success', 'Artículo actualizado.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación:', $e->errors());
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error general:', ['message' => $e->getMessage()]);
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el artículo: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('admin.articulos.index')->with('error', 'Error al actualizar el artículo.');
        }
    }

    public function destroy($id, Request $request)
    {
        try {
            $articulo = Articulo::findOrFail($id);

            // Guardar el título para el mensaje
            $tituloArticulo = $articulo->TITULO_ARTICULO;

            // Eliminar archivos asociados si existen
            if ($articulo->URL_ARTICULO) {
                Storage::delete($articulo->URL_ARTICULO);
            }

            if ($articulo->URL_IMAGEN_ARTICULO) {
                Storage::delete($articulo->URL_IMAGEN_ARTICULO);
            }

            // Eliminar relaciones con autores
            $articulo->autores()->detach();

            // Eliminar el artículo
            $articulo->delete();

            // ✅ RESPUESTA AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Artículo '{$tituloArticulo}' eliminado correctamente."
                ]);
            }

            return redirect()->route('admin.articulos.index')
                ->with('success', "Artículo '{$tituloArticulo}' eliminado correctamente.");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Artículo no encontrado
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El artículo no existe o ya fue eliminado.'
                ], 404);
            }

            return redirect()->route('admin.articulos.index')
                ->with('error', 'El artículo no existe o ya fue eliminado.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Error de base de datos (restricciones de foreign key, etc.)
            Log::error('Error de base de datos al eliminar artículo:', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el artículo porque está relacionado con otros elementos del sistema.'
                ], 422);
            }

            return redirect()->route('admin.articulos.index')
                ->with('error', 'No se puede eliminar el artículo porque está relacionado con otros elementos del sistema.');
        } catch (\Exception $e) {
            // Error general
            Log::error('Error general al eliminar artículo:', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error inesperado al eliminar el artículo. Inténtalo de nuevo.'
                ], 500);
            }

            return redirect()->route('admin.articulos.index')
                ->with('error', 'Error inesperado al eliminar el artículo. Inténtalo de nuevo.');
        }
    }

    private function applyFilters($query, Request $request)
    {
        // Agregar al inicio del método applyFilters para debug
        Log::info('=== DEBUG FILTROS ===');
        Log::info('Request completo:', $request->all());
        Log::info('Tipos recibidos:', [
            'tipo_raw' => $request->input('tipo'),
            'tipo_array' => $request->input('tipo', []),
            'has_tipo' => $request->has('tipo'),
            'filled_tipo' => $request->filled('tipo')
        ]);

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
            $query->whereIn('ID_TIPO', $request->input('tipo'));
            // $tipos = $request->input('tipo');
            // $query->whereIn('ID_TIPO', $tipos);
        }

        $query = $this->applyYearFilters($query, $request, 'FECHA_ARTICULO', true);

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
        // return $query->exists();
        return $query;
    }

    private function validateArticulo(Request $request, $id = null)
    {
        $uniqueRule = $id ? 'unique:tb_articulo,ISSN_ARTICULO,' . $id . ',ID_ARTICULO' : 'unique:tb_articulo,ISSN_ARTICULO';

        $request->validate([
            'issn_articulos' => 'required|string|max:9|' . $uniqueRule,
            'titulo_articulos' => 'required|string|max:255',
            'resumen_articulos' => 'required|string',
            'fecha_articulos' => 'required|date',
            'revista_articulos' => 'required|string|max:100',
            'id_tipo' => 'required|integer',
            'url_revista_articulos' => 'nullable|url',
            'url_articulos' => 'nullable|file|mimes:pdf',
            'url_imagen_articulos' => 'nullable|file|mimes:png,jpg,jpeg,webp',
            'nombre_autores' => 'required|array',
            'apellido_autores' => 'required|array',
        ]);
    }

    private function assignArticuloData(Articulo $articulo, Request $request)
    {
        $articulo->ISSN_ARTICULO = $request->issn_articulos;
        $articulo->TITULO_ARTICULO = $request->titulo_articulos;
        $articulo->RESUMEN_ARTICULO = $request->resumen_articulos;
        $articulo->FECHA_ARTICULO = $request->fecha_articulos;
        $articulo->REVISTA_ARTICULO = $request->revista_articulos;
        $articulo->ID_TIPO = $request->id_tipo; 
        $articulo->URL_REVISTA_ARTICULO = $request->url_revista_articulos;

        $articulo->ID_USUARIO = Auth::id();

        if ($request->hasFile('url_articulos')) {
            $articulo->URL_ARTICULO = $this->handleFileUpload($request, 'url_articulos', 'articulos');
        }

        if ($request->hasFile('url_imagen_articulos')) {
            $articulo->URL_IMAGEN_ARTICULO = $this->handleFileUpload($request, 'url_imagen_articulos', 'imagenes');
        }
    }
}
