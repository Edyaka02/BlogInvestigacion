<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Core\Evento;
use App\Models\Options\Tipo;
use App\Models\Options\Modalidad;
use App\Models\Options\Comunicacion;
use App\Models\Options\Ambito;
use App\Traits\Archivos;
use App\Traits\AutorTrait;
use App\Traits\YearTrait;
use App\Traits\OpcionesTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EventoController extends Controller
{
    use Archivos, AutorTrait, YearTrait, OpcionesTrait;

    /**
     * Display a listing of eventos for admin
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = Evento::select('tb_evento.*')
                ->distinct()
                ->with(['autores']);

            // Aplicar filtros
            $this->applyFilters($query, $request);

            // Obtener datos paginados
            $eventos = $query->paginate($request->get('per_page', 15))
                ->appends($request->except('page'));

            // Procesar URLs de imágenes
            foreach ($eventos as $evento) {
                if ($evento->URL_IMAGEN_EVENTO) {
                    $evento->URL_IMAGEN_EVENTO = Storage::url($evento->URL_IMAGEN_EVENTO);
                }
                if ($evento->URL_EVENTO) {
                    $evento->URL_EVENTO = Storage::url($evento->URL_EVENTO);
                }
            }

            // Datos adicionales para filtros
            $tipos = Tipo::pluck('NOMBRE_TIPO', 'ID_TIPO');
            $modalidades = Modalidad::pluck('NOMBRE_MODALIDAD', 'ID_MODALIDAD');
            $comunicaciones = Comunicacion::pluck('NOMBRE_COMUNICACION', 'ID_COMUNICACION');
            $ambitos = Ambito::pluck('NOMBRE_AMBITO', 'ID_AMBITO');
            $years = $this->applyYears(2);

            return response()->json([
                'success' => true,
                'data' => [
                    'eventos' => $eventos,
                    'tipos' => $tipos,
                    'modalidades' => $modalidades,
                    'comunicaciones' => $comunicaciones,
                    'ambitos' => $ambitos,
                    'years' => $years
                ],
                'message' => 'Eventos obtenidos correctamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error en API EventoController@index:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los eventos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created evento
     */
    public function store(Request $request)
    {
        try {
            $this->validateEvento($request);

            $evento = new Evento();
            $this->assignEventoData($evento, $request);
            $evento->save();

            // Guardar autores
            $autores = $this->handleAutores($request);
            $evento->autores()->sync($autores);

            return response()->json([
                'success' => true,
                'message' => 'Evento creado correctamente.',
                'data' => $evento->load(['autores'])
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en API EventoController@store:', $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al crear el evento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified evento
     */
    public function show($id)
    {
        try {
            $evento = Evento::with(['autores'])->findOrFail($id);

            // Generar URLs públicas
            if ($evento->URL_EVENTO) {
                $evento->URL_EVENTO = Storage::url($evento->URL_EVENTO);
            }
            if ($evento->URL_IMAGEN_EVENTO) {
                $evento->URL_IMAGEN_EVENTO = Storage::url($evento->URL_IMAGEN_EVENTO);
            }

            return response()->json([
                'success' => true,
                'data' => $evento,
                'message' => 'Evento obtenido correctamente'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Evento no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el evento'
            ], 500);
        }
    }

    /**
     * Update the specified evento
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validateEvento($request, $id);

            $evento = Evento::findOrFail($id);
            $this->assignEventoData($evento, $request);
            $evento->save();

            // Guardar autores
            $autores = $this->handleAutores($request);
            $evento->autores()->sync($autores);

            return response()->json([
                'success' => true,
                'message' => 'Evento actualizado correctamente.',
                'data' => $evento->load(['autores'])
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
                'message' => 'Error al actualizar el evento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified evento
     */
    public function destroy($id)
    {
        try {
            $evento = Evento::findOrFail($id);
            $tituloEvento = $evento->TITULO_EVENTO;

            // Eliminar archivos asociados
            if ($evento->URL_EVENTO) {
                Storage::delete($evento->URL_EVENTO);
            }
            if ($evento->URL_IMAGEN_EVENTO) {
                Storage::delete($evento->URL_IMAGEN_EVENTO);
            }

            // Eliminar relaciones con autores
            $evento->autores()->detach();

            // Eliminar el evento
            $evento->delete();

            return response()->json([
                'success' => true,
                'message' => "Evento '{$tituloEvento}' eliminado correctamente."
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'El evento no existe o ya fue eliminado.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el evento.'
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
                    $q->orWhere('TITULO_EVENTO', 'like', "%$term%")
                        ->orWhere('NOMBRE_EVENTO', 'like', "%$term%")
                        ->orWhere('RESUMEN_EVENTO', 'like', "%$term%")
                        ->orWhere('EJE_TEMATICO_EVENTO', 'like', "%$term%")
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

        // Filtro por modalidad
        if ($request->filled('modalidad') && !empty($request->input('modalidad'))) {
            $query->whereIn('ID_MODALIDAD', $request->input('modalidad'));
        }

        // Filtro por comunicación
        if ($request->filled('comunicacion') && !empty($request->input('comunicacion'))) {
            $query->whereIn('ID_COMUNICACION', $request->input('comunicacion'));
        }

        // Filtro por ámbito
        if ($request->filled('ambito') && !empty($request->input('ambito'))) {
            $query->whereIn('ID_AMBITO', $request->input('ambito'));
        }

        // Filtros de año (usando el trait)
        $query = $this->applyYearFilters($query, $request, 'FECHA_EVENTO', true);

        // Ordenamiento
        $ordenar = $request->input('ordenar', 'fecha_desc');
        switch ($ordenar) {
            case 'titulo_asc':
                $query->orderBy('TITULO_EVENTO', 'asc');
                break;
            case 'titulo_desc':
                $query->orderBy('TITULO_EVENTO', 'desc');
                break;
            case 'fecha_asc':
                $query->orderBy('FECHA_EVENTO', 'asc');
                break;
            case 'fecha_desc':
                $query->orderBy('FECHA_EVENTO', 'desc');
                break;
        }

        return $query;
    }

    /**
     * Validate evento data
     */
    private function validateEvento(Request $request, $id = null)
    {
        $request->validate([
            'titulo_evento' => 'required|string|max:255',
            'resumen_evento' => 'required|string',
            'fecha_evento' => 'required|date',
            'nombre_evento' => 'required|string|max:100',
            'id_tipo' => 'required|integer|exists:tb_tipo,ID_TIPO',
            'id_modalidad' => 'required|integer|exists:tb_modalidad,ID_MODALIDAD',
            'id_comunicacion' => 'required|integer|exists:tb_comunicacion,ID_COMUNICACION',
            'id_ambito' => 'required|integer|exists:tb_ambito,ID_AMBITO',
            'eje_tematico_evento' => 'required|string|max:255',
            'url_evento' => 'nullable|file|mimes:pdf',
            'url_imagen_evento' => 'nullable|file|mimes:png,jpg,jpeg,webp',
            'nombre_autores' => 'required|array',
            'apellido_autores' => 'required|array',
        ]);
    }

    /**
     * Assign evento data
     */
    private function assignEventoData(Evento $evento, Request $request)
    {
        $evento->TITULO_EVENTO = $request->titulo_evento;
        $evento->RESUMEN_EVENTO = $request->resumen_evento;
        $evento->FECHA_EVENTO = $request->fecha_evento;
        $evento->NOMBRE_EVENTO = $request->nombre_evento;
        $evento->ID_TIPO = $request->id_tipo;
        $evento->ID_MODALIDAD = $request->id_modalidad;
        $evento->ID_COMUNICACION = $request->id_comunicacion;
        $evento->ID_AMBITO = $request->id_ambito;
        $evento->EJE_TEMATICO_EVENTO = $request->eje_tematico_evento;
        $evento->ID_USUARIO = Auth::id();

        if ($request->hasFile('url_evento')) {
            $evento->URL_EVENTO = $this->handleFileUpload($request, 'url_evento', 'eventos');
        }

        if ($request->hasFile('url_imagen_evento')) {
            $evento->URL_IMAGEN_EVENTO = $this->handleFileUpload($request, 'url_imagen_evento', 'imagenes');
        }
    }
}