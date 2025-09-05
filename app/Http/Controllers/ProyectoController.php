<?php
// filepath: c:\laragon\www\BlogInvestigacion\app\Http\Controllers\ProyectoController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Core\Proyecto;
use App\Models\Options\Organismo;
use App\Models\Options\Ambito;
use App\Traits\FilesTrait;
use App\Traits\AuthorTrait;
use App\Traits\YearTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProyectoController extends Controller
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
     * Muestra los proyectos.
     */
    public function index(Request $request)
    {
        $query = Proyecto::with(['autores', 'organismo', 'ambito'])
            ->select('ID_PROYECTO', 'TITULO_PROYECTO', 'RESUMEN_PROYECTO', 'FECHA_PROYECTO', 'CONVOCATORIA_PROYECTO', 'ID_ORGANISMO', 'ID_AMBITO', 'URL_IMAGEN_PROYECTO');

        $this->applyFilters($query, $request);

        $proyectos = $query->paginate(30)->appends($request->except('page'));

        if ($request->ajax()) {
            foreach ($proyectos as $proyecto) {
                foreach ($proyecto->autores as $autor) {
                    $this->splitAuthorName($autor);
                }

                // ✅ Procesar URLs de imagen
                if ($proyecto->URL_IMAGEN_PROYECTO) {
                    if (!str_starts_with($proyecto->URL_IMAGEN_PROYECTO, 'storage/')) {
                        $proyecto->URL_IMAGEN_PROYECTO = 'storage/' . $proyecto->URL_IMAGEN_PROYECTO;
                    }
                    if (!str_starts_with($proyecto->URL_IMAGEN_PROYECTO, '/')) {
                        $proyecto->URL_IMAGEN_PROYECTO = '/' . $proyecto->URL_IMAGEN_PROYECTO;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'proyectos' => $proyectos,
            ]);
        }

        $organismos = Organismo::pluck('NOMBRE_ORGANISMO', 'ID_ORGANISMO');
        $ambitos = Ambito::pluck('NOMBRE_AMBITO', 'ID_AMBITO');
        $years = $this->applyYears(2);

        return view('entities.proyectos.index', compact('years', 'organismos', 'ambitos'));
    }

    /**
     * Muestra los proyectos en el panel de administración.
     */
    public function adminIndex(Request $request)
    {
        $query = Proyecto::select('tb_proyecto.*')
            ->distinct()
            ->with(['autores', 'organismo', 'ambito']);

        $this->applyFilters($query, $request);

        $proyectos = $query->paginate(30)->appends($request->except('page'));

        if ($request->ajax()) {
            foreach ($proyectos as $proyecto) {
                if ($proyecto->URL_IMAGEN_PROYECTO && !str_starts_with($proyecto->URL_IMAGEN_PROYECTO, 'storage/')) {
                    $proyecto->URL_IMAGEN_PROYECTO = 'storage/' . $proyecto->URL_IMAGEN_PROYECTO;
                }
            }

            return response()->json([
                'proyectos' => $proyectos,
            ]);
        }

        $organismos = Organismo::pluck('NOMBRE_ORGANISMO', 'ID_ORGANISMO');
        $ambitos = Ambito::pluck('NOMBRE_AMBITO', 'ID_AMBITO');
        $years = $this->applyYears(2);

        return view('entities.proyectos.edit', compact('years', 'organismos', 'ambitos'));
    }

    /**
     * Muestra el proyecto.
     */
    public function show($id)
    {
        $proyecto = Proyecto::with(['autores', 'organismo', 'ambito'])->findOrFail($id);

        $proyecto->increment('VISTA_PROYECTO');

        // ✅ Procesar URLs con nueva estructura
        if ($proyecto->URL_PROYECTO) {
            if (!str_starts_with($proyecto->URL_PROYECTO, '/')) {
                $proyecto->URL_PROYECTO = '/' . $proyecto->URL_PROYECTO;
            }
        }

        if ($proyecto->URL_IMAGEN_PROYECTO) {
            if (!str_starts_with($proyecto->URL_IMAGEN_PROYECTO, '/')) {
                $proyecto->URL_IMAGEN_PROYECTO = '/' . $proyecto->URL_IMAGEN_PROYECTO;
            }
        }

        return view('entities.proyectos.show', compact('proyecto'));
    }

    /**
     * Descarga el proyecto.
     */
    public function download($id)
    {
        $proyecto = Proyecto::findOrFail($id);

        // 🎯 Incrementar descarga
        $proyecto->increment('DESCARGA_PROYECTO');

        if (empty($proyecto->URL_PROYECTO)) {
            abort(404, 'Archivo no disponible');
        }

        $filePath = public_path(ltrim($proyecto->URL_PROYECTO, '/'));

        if (!file_exists($filePath)) {
            abort(404, 'Archivo no encontrado');
        }

        $fileName = Str::slug($proyecto->TITULO_PROYECTO) . '.pdf';

        return response()->download($filePath, $fileName);
    }

    /**
     * Almacena el proyecto en la base de datos.
     */
    public function store(Request $request)
    {
        try {
            $this->validateData($request);

            $proyecto = new Proyecto();
            $this->assignData($proyecto, $request);
            $proyecto->save();

            $this->assignFiles($proyecto, $request);

            // Guardar autores
            $autores = $this->handleAutores($request);
            $proyecto->autores()->sync($autores);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Proyecto creado correctamente.',
                    'proyecto' => $proyecto->load(['autores', 'organismo', 'ambito'])
                ]);
            }

            return redirect()->route('entities.dashboard.dashboard')->with('success', 'Proyecto registrado.');
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
                    'message' => 'Error al crear el proyecto: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('entities.dashboard.dashboard')->with('error', 'Error al crear el proyecto.');
        }
    }

    /**
     * Actualiza el proyecto en la base de datos.
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validateData($request, $id);

            $proyecto = Proyecto::findOrFail($id);
            $this->assignData($proyecto, $request);
            $proyecto->save();

            $this->assignFiles($proyecto, $request);

            // Guardar autores
            $autores = $this->handleAutores($request);
            $proyecto->autores()->sync($autores);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Proyecto actualizado.',
                    'proyecto' => $proyecto->load(['autores', 'organismo', 'ambito'])
                ]);
            }

            return redirect()->route('admin.proyectos.index')->with('success', 'Proyecto actualizado.');
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
                    'message' => 'Error al actualizar el proyecto: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('admin.proyectos.index')->with('error', 'Error al actualizar el proyecto.');
        }
    }

    /**
     * Elimina el proyecto de la base de datos.
     */
    public function destroy($id, Request $request)
    {
        try {
            $proyecto = Proyecto::findOrFail($id);
            $tituloProyecto = $proyecto->TITULO_PROYECTO;

            // 🎯 Eliminar archivos asociados
            if ($proyecto->URL_PROYECTO) {
                $this->deleteFile($proyecto->URL_PROYECTO);
            }

            if ($proyecto->URL_IMAGEN_PROYECTO) {
                $this->deleteFile($proyecto->URL_IMAGEN_PROYECTO);
            }

            $proyecto->autores()->detach();
            $proyecto->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Proyecto '{$tituloProyecto}' eliminado correctamente."
                ]);
            }

            return redirect()->route('admin.proyectos.index')
                ->with('success', "Proyecto '{$tituloProyecto}' eliminado correctamente.");
        } catch (\Exception $e) {
            Log::error('Error al eliminar proyecto:', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error inesperado al eliminar el proyecto.'
                ], 500);
            }

            return redirect()->route('admin.proyectos.index')
                ->with('error', 'Error inesperado al eliminar el proyecto.');
        }
    }

    /**
     * Aplica los filtros a la consulta de proyectos.
     */
    private function applyFilters($query, Request $request)
    {
        Log::info('=== DEBUG FILTROS PROYECTOS ===');
        Log::info('Request completo:', $request->all());

        if ($request->has('search') && !is_null($request->input('search'))) {
            $search = $request->input('search');
            $searchTerms = explode(' ', $search);

            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->orWhere('TITULO_PROYECTO', 'like', "%$term%")
                        ->orWhere('RESUMEN_PROYECTO', 'like', "%$term%")
                        ->orWhere('CONVOCATORIA_PROYECTO', 'like', "%$term%")
                        ->orWhereHas('autores', function ($q) use ($term) {
                            $q->where('NOMBRE_AUTOR', 'like', "%$term%")
                                ->orWhere('APELLIDO_AUTOR', 'like', "%$term%");
                        })
                        ->orWhereHas('organismo', function ($q) use ($term) {
                            $q->where('NOMBRE_ORGANISMO', 'like', "%$term%");
                        })
                        ->orWhereHas('ambito', function ($q) use ($term) {
                            $q->where('NOMBRE_AMBITO', 'like', "%$term%");
                        });
                }
            });
        }

        // Filtro por organismo
        if ($request->has('organismo') && !empty($request->input('organismo'))) {
            $query->whereIn('ID_ORGANISMO', $request->input('organismo'));
        }

        // Filtro por ámbito
        if ($request->has('ambito') && !empty($request->input('ambito'))) {
            $query->whereIn('ID_AMBITO', $request->input('ambito'));
        }

        $query = $this->applyYearFilters($query, $request, 'FECHA_PROYECTO', true);

        $ordenar = $request->input('ordenar', 'fecha_desc');
        switch ($ordenar) {
            case 'titulo_asc':
                $query->orderBy('TITULO_PROYECTO', 'asc');
                break;
            case 'titulo_desc':
                $query->orderBy('TITULO_PROYECTO', 'desc');
                break;
            case 'fecha_asc':
                $query->orderBy('FECHA_PROYECTO', 'asc');
                break;
            case 'fecha_desc':
                $query->orderBy('FECHA_PROYECTO', 'desc');
                break;
            case 'organismo_asc':
                $query->join('tb_organismo', 'tb_proyecto.ID_ORGANISMO', '=', 'tb_organismo.ID_ORGANISMO')
                      ->orderBy('tb_organismo.NOMBRE_ORGANISMO', 'asc');
                break;
            case 'organismo_desc':
                $query->join('tb_organismo', 'tb_proyecto.ID_ORGANISMO', '=', 'tb_organismo.ID_ORGANISMO')
                      ->orderBy('tb_organismo.NOMBRE_ORGANISMO', 'desc');
                break;
        }

        return $query;
    }

    /**
     * Valida que los datos del proyecto sean los correctos.
     */
    private function validateData(Request $request, $id = null)
    {
        $request->validate([
            'titulo_proyectos' => 'required|string|max:255',
            'resumen_proyectos' => 'nullable|string',
            'fecha_proyectos' => 'required|date',
            'convocatoria_proyectos' => 'required|string|max:255',
            'id_organismo' => 'required|exists:tb_organismo,ID_ORGANISMO',
            'id_ambito' => 'required|exists:tb_ambito,ID_AMBITO',
            'url_proyectos' => 'nullable|file|mimes:pdf',
            'url_imagen_proyectos' => 'nullable|file|mimes:png,jpg,jpeg,webp',
            'nombre_autores' => 'required|array',
            'apellido_autores' => 'required|array',
        ]);
    }

    /**
     * Asigna los datos del proyecto a la instancia del modelo.
     */
    private function assignData(Proyecto $proyecto, Request $request)
    {
        $proyecto->TITULO_PROYECTO = $request->titulo_proyectos;
        $proyecto->RESUMEN_PROYECTO = $request->resumen_proyectos;
        $proyecto->FECHA_PROYECTO = $request->fecha_proyectos;
        $proyecto->CONVOCATORIA_PROYECTO = $request->convocatoria_proyectos;
        $proyecto->ID_ORGANISMO = $request->id_organismo;
        $proyecto->ID_AMBITO = $request->id_ambito;

        if (!$proyecto->exists) {
            $proyecto->VISTA_PROYECTO = 0;
            $proyecto->DESCARGA_PROYECTO = 0;
        }

        $proyecto->ID_USUARIO = Auth::id();
    }

    /**
     * Asigna los archivos del proyecto.
     */
    private function assignFiles(Proyecto $proyecto, Request $request)
    {
        $uploadedFiles = $this->handleMultipleFileUploadsSimple($request, 'proyectos', $proyecto->ID_PROYECTO, [
            'url_proyectos',
            'url_imagen_proyectos'
        ]);

        if (isset($uploadedFiles['url_proyectos'])) {
            // Eliminar archivo anterior
            if ($proyecto->URL_PROYECTO) {
                $this->deleteFile($proyecto->URL_PROYECTO);
            }
            $proyecto->URL_PROYECTO = $uploadedFiles['url_proyectos'];
        }

        if (isset($uploadedFiles['url_imagen_proyectos'])) {
            // Eliminar imagen anterior
            if ($proyecto->URL_IMAGEN_PROYECTO) {
                $this->deleteFile($proyecto->URL_IMAGEN_PROYECTO);
            }
            $proyecto->URL_IMAGEN_PROYECTO = $uploadedFiles['url_imagen_proyectos'];
        }

        // Guardar cambios de archivos
        if (!empty($uploadedFiles)) {
            $proyecto->save();
        }
    }
}