<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Core\Articulo;
use App\Models\Options\Tipo;
use App\Traits\FilesTrait;
use App\Traits\AuthorTrait;
use App\Traits\YearTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ArticuloController extends Controller
{
    use FilesTrait, AuthorTrait, YearTrait;

    public function __construct()
    {
        $this->middleware('auth')->only([
            'adminIndex',
            'store',
            'update',
            'destroy'
        ]);
    }

    /**
     * Muestra los artículos.
     */
    public function index(Request $request)
    {
        $query = Articulo::with('autores')
            ->select('ID_ARTICULO', 'ISSN_ARTICULO', 'TITULO_ARTICULO', 'FECHA_ARTICULO', 'REVISTA_ARTICULO', 'URL_IMAGEN_ARTICULO');

        $this->applyFilters($query, $request);

        $articulos = $query->paginate(30)->appends($request->except('page'));

        if ($request->ajax()) {
            foreach ($articulos as $articulo) {
                foreach ($articulo->autores as $autor) {
                    $this->splitAuthorName($autor);
                }

                // ✅ CAMBIO: URLs ya incluyen storage/
                if ($articulo->URL_IMAGEN_ARTICULO) {
                    // Si ya empieza con storage/, no agregar nada
                    if (!str_starts_with($articulo->URL_IMAGEN_ARTICULO, 'storage/')) {
                        $articulo->URL_IMAGEN_ARTICULO = 'storage/' . $articulo->URL_IMAGEN_ARTICULO;
                    }
                    // Agregar la barra inicial si no la tiene
                    if (!str_starts_with($articulo->URL_IMAGEN_ARTICULO, '/')) {
                        $articulo->URL_IMAGEN_ARTICULO = '/' . $articulo->URL_IMAGEN_ARTICULO;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'articulos' => $articulos,
            ]);
        }

        $tipos = Tipo::pluck('NOMBRE_TIPO', 'ID_TIPO');
        $years = $this->applyYears(2);

        return view('entities.articulos.index', compact('years', 'tipos'));
    }

    /**
     * Muestra el artículo en el panel de administración.
     */
    public function adminIndex(Request $request)
    {
        $query = Articulo::select('tb_articulo.*')
            ->distinct()
            ->with(['autores', 'tipo']);

        $this->applyFilters($query, $request);

        $articulos = $query->paginate(30)->appends($request->except('page'));

        if ($request->ajax()) {
            foreach ($articulos as $articulo) {
                // ✅ CAMBIO: URL pública directa
                // if ($articulo->URL_IMAGEN_ARTICULO) {
                //     $articulo->URL_IMAGEN_ARTICULO = '/' . $articulo->URL_IMAGEN_ARTICULO;
                // }
                if (!str_starts_with($articulo->URL_IMAGEN_ARTICULO, 'storage/')) {
                    $articulo->URL_IMAGEN_ARTICULO = 'storage/' . $articulo->URL_IMAGEN_ARTICULO;
                }
            }

            return response()->json([
                'articulos' => $articulos,
            ]);
        }

        $tipos = Tipo::pluck('NOMBRE_TIPO', 'ID_TIPO');
        $years = $this->applyYears(2);

        return view('entities.articulos.edit', compact('years', 'tipos'));
    }

    /**
     * Muestra el artículo.
     */
    public function show($id)
    {
        $articulo = Articulo::with('autores')->findOrFail($id);

        $articulo->increment('VISTA_ARTICULO');

        // ✅ CAMBIO: Procesar URLs con nueva estructura
        if ($articulo->URL_ARTICULO) {
            if (!str_starts_with($articulo->URL_ARTICULO, '/')) {
                $articulo->URL_ARTICULO = '/' . $articulo->URL_ARTICULO;
            }
        }

        if ($articulo->URL_IMAGEN_ARTICULO) {
            if (!str_starts_with($articulo->URL_IMAGEN_ARTICULO, '/')) {
                $articulo->URL_IMAGEN_ARTICULO = '/' . $articulo->URL_IMAGEN_ARTICULO;
            }
        }

        return view('entities.articulos.show', compact('articulo'));
    }

    /**
     * Descarga el artículo.
     */
    public function download($id)
    {
        $articulo = Articulo::findOrFail($id);

        // 🎯 INCREMENTAR DESCARGA (1 LÍNEA!)
        $articulo->increment('DESCARGA_ARTICULO');

        if (empty($articulo->URL_ARTICULO)) {
            abort(404, 'Archivo no disponible');

        }

        $filePath = public_path(ltrim($articulo->URL_ARTICULO, '/'));
        // $filePath = storage_path(ltrim($articulo->URL_ARTICULO, '/'));

        if (!file_exists($filePath)) {
            abort(404, 'Archivo no encontrado');
        }

        $fileName = Str::slug($articulo->TITULO_ARTICULO) . '.pdf';

        return response()->download($filePath, $fileName);
    }

    /**
     * Almacena el artículo en la base de datos.
     */
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
            $this->validateData($request);

            $articulo = new Articulo();
            $this->assignData($articulo, $request);
            $articulo->save();

            $this->assignFiles($articulo, $request);

            // Guardar autores
            $autores = $this->handleAutores($request);
            $articulo->autores()->sync($autores);

            // Solo respuesta JSON para AJAX
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

    /**
     * Actualiza el artículo en la base de datos.
     */
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
            $this->validateData($request, $id);

            $articulo = Articulo::findOrFail($id);

            // Debug: ver artículo antes de actualizar
            Log::info('Artículo antes de actualizar:', $articulo->toArray());

            $this->assignData($articulo, $request);

            // Debug: ver artículo después de asignar datos
            Log::info('Artículo después de asignar datos:', $articulo->toArray());

            $articulo->save();

            $this->assignFiles($articulo, $request);

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

    /**
     * Elimina el artículo de la base de datos.
     */
    public function destroy($id, Request $request)
    {
        try {
            $articulo = Articulo::findOrFail($id);
            $tituloArticulo = $articulo->TITULO_ARTICULO;

            // 🎯 USAR EL NUEVO SISTEMA DE ELIMINACIÓN
            if ($articulo->URL_ARTICULO) {
                $this->deleteFile($articulo->URL_ARTICULO);
            }

            if ($articulo->URL_IMAGEN_ARTICULO) {
                $this->deleteFile($articulo->URL_IMAGEN_ARTICULO);
            }

            $articulo->autores()->detach();
            $articulo->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Artículo '{$tituloArticulo}' eliminado correctamente."
                ]);
            }

            return redirect()->route('admin.articulos.index')
                ->with('success', "Artículo '{$tituloArticulo}' eliminado correctamente.");
        } catch (\Exception $e) {
            Log::error('Error al eliminar artículo:', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error inesperado al eliminar el artículo.'
                ], 500);
            }

            return redirect()->route('admin.articulos.index')
                ->with('error', 'Error inesperado al eliminar el artículo.');
        }
    }

    /**
     * Aplica los filtros a la consulta de artículos.
     */
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

    /**
     * Valida que los datos del artículo sean los correctos.
     */
    private function validateData(Request $request, $id = null)
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

    /**
     * Asigna los datos del artículo a la instancia del modelo.
     */
    private function assignData(Articulo $articulo, Request $request)
    {
        $articulo->ISSN_ARTICULO = $request->issn_articulos;
        $articulo->TITULO_ARTICULO = $request->titulo_articulos;
        $articulo->RESUMEN_ARTICULO = $request->resumen_articulos;
        $articulo->FECHA_ARTICULO = $request->fecha_articulos;
        $articulo->REVISTA_ARTICULO = $request->revista_articulos;
        $articulo->ID_TIPO = $request->id_tipo;
        $articulo->URL_REVISTA_ARTICULO = $request->url_revista_articulos;

        if (!$articulo->exists) {
            $articulo->VISTA_ARTICULO = 0;
            $articulo->DESCARGA_ARTICULO = 0;
        }

        $articulo->ID_USUARIO = Auth::id();
    }

    /**
     * Asigna los archivos del artículo.
     */
    private function assignFiles(Articulo $articulo, Request $request)
    {
        $uploadedFiles = $this->handleMultipleFileUploadsSimple($request, 'articulos', $articulo->ID_ARTICULO, [
            'url_articulos',
            'url_imagen_articulos'
        ]);

        if (isset($uploadedFiles['url_articulos'])) {
            // Eliminar archivo anterior
            if ($articulo->URL_ARTICULO) {
                $this->deleteFile($articulo->URL_ARTICULO);
            }
            $articulo->URL_ARTICULO = $uploadedFiles['url_articulos'];
        }

        if (isset($uploadedFiles['url_imagen_articulos'])) {
            // Eliminar imagen anterior
            if ($articulo->URL_IMAGEN_ARTICULO) {
                $this->deleteFile($articulo->URL_IMAGEN_ARTICULO);
            }
            $articulo->URL_IMAGEN_ARTICULO = $uploadedFiles['url_imagen_articulos'];
        }

        // GUARDAR CAMBIOS DE ARCHIVOS
        if (!empty($uploadedFiles)) {
            $articulo->save();
        }
    }
}
