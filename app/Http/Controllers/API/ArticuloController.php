<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Core\Articulo;
use App\Models\Options\Tipo;
use App\Traits\Archivos;
use App\Traits\AutorTrait;
use App\Traits\YearTrait;
use App\Traits\OpcionesTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ArticuloController extends Controller
{
    use Archivos, AutorTrait, YearTrait, OpcionesTrait;

    /**
     * Display a listing of articulos for admin
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = Articulo::select('tb_articulo.*')
                ->distinct()
                ->with(['autores', 'tipo']);

            // Aplicar filtros
            $this->applyFilters($query, $request);

            // Obtener datos paginados
            $articulos = $query->paginate($request->get('per_page', 15))
                ->appends($request->except('page'));

            // // Procesar URLs de imágenes
            // foreach ($articulos as $articulo) {
            //     if ($articulo->URL_IMAGEN_ARTICULO) {
            //         $articulo->URL_IMAGEN_ARTICULO = Storage::url($articulo->URL_IMAGEN_ARTICULO);
            //     }
            //     if ($articulo->URL_ARTICULO) {
            //         $articulo->URL_ARTICULO = Storage::url($articulo->URL_ARTICULO);
            //     }
            // }
            // ✅ GENERAR URLs SEGURAS en lugar de Storage::url()
            foreach ($articulos as $articulo) {
                if ($articulo->URL_ARTICULO) {
                    // URL con control de acceso
                    $articulo->download_url = route('articulo.download', [
                        'filename' => basename($articulo->URL_ARTICULO)
                    ]);
                }
                if ($articulo->URL_IMAGEN_ARTICULO) {
                    // URL para imagen
                    $articulo->imagen_url = route('imagen.show', [
                        'filename' => basename($articulo->URL_IMAGEN_ARTICULO)
                    ]);
                }
            }

            // Datos adicionales para filtros
            $tipos = Tipo::pluck('NOMBRE_TIPO', 'ID_TIPO');
            $years = $this->applyYears(2);

            return response()->json([
                'success' => true,
                'data' => [
                    'articulos' => $articulos,
                    'tipos' => $tipos,
                    'years' => $years
                ],
                'message' => 'Artículos obtenidos correctamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error en API ArticuloController@index:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los artículos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created articulo
     */
    public function store(Request $request)
    {
        // Verificar si el ISSN ya existe
        if (Articulo::where('ISSN_ARTICULO', $request->issn_articulo)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'El ISSN ya existe.'
            ], 422);
        }

        try {
            $this->validateArticulo($request);

            $articulo = new Articulo();
            $this->assignArticuloData($articulo, $request);
            $articulo->save();

            // Guardar autores
            $autores = $this->handleAutores($request);
            $articulo->autores()->sync($autores);

            return response()->json([
                'success' => true,
                'message' => 'Artículo creado correctamente.',
                'data' => $articulo->load(['autores', 'tipo'])
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en API ArticuloController@store:', $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al crear el artículo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified articulo
     */
    public function show($id)
    {
        try {
            $articulo = Articulo::with(['autores', 'tipo'])->findOrFail($id);

            // Generar URLs públicas
            if ($articulo->URL_ARTICULO) {
                $articulo->URL_ARTICULO = Storage::url($articulo->URL_ARTICULO);
            }
            if ($articulo->URL_IMAGEN_ARTICULO) {
                $articulo->URL_IMAGEN_ARTICULO = Storage::url($articulo->URL_IMAGEN_ARTICULO);
            }

            return response()->json([
                'success' => true,
                'data' => $articulo,
                'message' => 'Artículo obtenido correctamente'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Artículo no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el artículo'
            ], 500);
        }
    }

    /**
     * Update the specified articulo
     */
    public function update(Request $request, $id)
    {
        // Verificar ISSN único
        if (Articulo::where('ISSN_ARTICULO', $request->issn_articulo)
            ->where('ID_ARTICULO', '!=', $id)->exists()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'El ISSN ya existe.'
            ], 422);
        }

        try {
            $this->validateArticulo($request, $id);

            $articulo = Articulo::findOrFail($id);
            $this->assignArticuloData($articulo, $request);
            $articulo->save();

            // Guardar autores
            $autores = $this->handleAutores($request);
            $articulo->autores()->sync($autores);

            return response()->json([
                'success' => true,
                'message' => 'Artículo actualizado correctamente.',
                'data' => $articulo->load(['autores', 'tipo'])
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el artículo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified articulo
     */
    public function destroy($id)
    {
        try {
            $articulo = Articulo::findOrFail($id);
            $tituloArticulo = $articulo->TITULO_ARTICULO;

            // Eliminar archivos asociados
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

            return response()->json([
                'success' => true,
                'message' => "Artículo '{$tituloArticulo}' eliminado correctamente."
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'El artículo no existe o ya fue eliminado.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el artículo.'
            ], 500);
        }
    }

    /**
     * Apply filters to the query
     */
    private function applyFilters($query, Request $request)
    {
        // Búsqueda por texto
        if ($request->filled('search')) {
            $search = $request->input('search');
            $searchTerms = explode(' ', $search);

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

        // Filtro por tipo
        if ($request->filled('tipo') && !empty($request->input('tipo'))) {
            $query->whereIn('ID_TIPO', $request->input('tipo'));
        }

        // Filtros de año (usando el trait)
        $query = $this->applyYearFilters($query, $request, 'FECHA_ARTICULO', true);

        // Ordenamiento
        $ordenar = $request->input('ordenar', 'fecha_desc');
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

        return $query;
    }

    private function validateArticulo(Request $request, $id = null)
    {
        $uniqueRule = $id ? 'unique:tb_articulo,ISSN_ARTICULO,' . $id . ',ID_ARTICULO' : 'unique:tb_articulo,ISSN_ARTICULO';

        $request->validate([
            'issn_articulo' => 'required|string|max:9|' . $uniqueRule,
            'titulo_articulo' => 'required|string|max:255',
            'resumen_articulo' => 'required|string',
            'fecha_articulo' => 'required|date',
            'revista_articulo' => 'required|string|max:100',
            'id_tipo' => 'required|integer',
            'url_revista_articulo' => 'required|url',
            'url_articulo' => 'nullable|file|mimes:pdf',
            'url_imagen_articulo' => 'nullable|file|mimes:png,jpg,jpeg,webp',
            'nombre_autores' => 'required|array',
            'apellido_autores' => 'required|array',
        ]);
    }

    private function assignArticuloData(Articulo $articulo, Request $request)
    {
        $articulo->ISSN_ARTICULO = $request->issn_articulo;
        $articulo->TITULO_ARTICULO = $request->titulo_articulo;
        $articulo->RESUMEN_ARTICULO = $request->resumen_articulo;
        $articulo->FECHA_ARTICULO = $request->fecha_articulo;
        $articulo->REVISTA_ARTICULO = $request->revista_articulo;
        // Cambiar esta línea:
        $articulo->ID_TIPO = $request->id_tipo; // Era tipo_articulo, ahora id_tipo
        $articulo->URL_REVISTA_ARTICULO = $request->url_revista_articulo;

        $articulo->ID_USUARIO = Auth::id();

        if ($request->hasFile('url_articulo')) {
            $articulo->URL_ARTICULO = $this->handleFileUpload($request, 'url_articulo', 'articulos');
        }

        if ($request->hasFile('url_imagen_articulo')) {
            $articulo->URL_IMAGEN_ARTICULO = $this->handleFileUpload($request, 'url_imagen_articulo', 'imagenes');
        }
    }
}
