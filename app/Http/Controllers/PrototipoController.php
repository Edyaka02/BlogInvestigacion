<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Core\Prototipo;
use App\Traits\FilesTrait;
use App\Traits\AuthorTrait;
use App\Traits\YearTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PrototipoController extends Controller
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
     * Muestra los prototipos.
     */
    public function index(Request $request)
    {
        $query = Prototipo::with('autores')
            ->select('ID_PROTOTIPO', 'NOMBRE_PROTOTIPO', 'PROPOSITO_PROTOTIPO', 'INSTITUCION_PROTOTIPO', 'DESCRIPCION_PROTOTIPO', 'FECHA_PROTOTIPO', 'URL_IMAGEN_PROTOTIPO');

        $this->applyFilters($query, $request);

        $prototipos = $query->paginate(30)->appends($request->except('page'));

        if ($request->ajax()) {
            foreach ($prototipos as $prototipo) {
                foreach ($prototipo->autores as $autor) {
                    $this->splitAuthorName($autor);
                }

                // ✅ Procesar URLs de imagen
                if ($prototipo->URL_IMAGEN_PROTOTIPO) {
                    if (!str_starts_with($prototipo->URL_IMAGEN_PROTOTIPO, 'storage/')) {
                        $prototipo->URL_IMAGEN_PROTOTIPO = 'storage/' . $prototipo->URL_IMAGEN_PROTOTIPO;
                    }
                    if (!str_starts_with($prototipo->URL_IMAGEN_PROTOTIPO, '/')) {
                        $prototipo->URL_IMAGEN_PROTOTIPO = '/' . $prototipo->URL_IMAGEN_PROTOTIPO;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'prototipos' => $prototipos,
            ]);
        }

        $years = $this->applyYears(2);

        return view('entities.prototipos.index', compact('years'));
    }

    /**
     * Muestra los prototipos en el panel de administración.
     */
    public function adminIndex(Request $request)
    {
        $query = Prototipo::select('tb_prototipo.*')
            ->distinct()
            ->with(['autores']);

        $this->applyFilters($query, $request);

        $prototipos = $query->paginate(30)->appends($request->except('page'));

        if ($request->ajax()) {
            foreach ($prototipos as $prototipo) {
                if ($prototipo->URL_IMAGEN_PROTOTIPO && !str_starts_with($prototipo->URL_IMAGEN_PROTOTIPO, 'storage/')) {
                    $prototipo->URL_IMAGEN_PROTOTIPO = 'storage/' . $prototipo->URL_IMAGEN_PROTOTIPO;
                }
            }

            return response()->json([
                'prototipos' => $prototipos,
            ]);
        }

        $years = $this->applyYears(2);

        return view('entities.prototipos.edit', compact('years'));
    }

    /**
     * Muestra el prototipo.
     */
    public function show($id)
    {
        $prototipo = Prototipo::with('autores')->findOrFail($id);

        $prototipo->increment('VISTA_PROTOTIPO');

        // ✅ Procesar URLs con nueva estructura
        if ($prototipo->URL_PROTOTIPO) {
            if (!str_starts_with($prototipo->URL_PROTOTIPO, '/')) {
                $prototipo->URL_PROTOTIPO = '/' . $prototipo->URL_PROTOTIPO;
            }
        }

        if ($prototipo->URL_IMAGEN_PROTOTIPO) {
            if (!str_starts_with($prototipo->URL_IMAGEN_PROTOTIPO, '/')) {
                $prototipo->URL_IMAGEN_PROTOTIPO = '/' . $prototipo->URL_IMAGEN_PROTOTIPO;
            }
        }

        return view('entities.prototipos.show', compact('prototipo'));
    }

    /**
     * Descarga el prototipo.
     */
    public function download($id)
    {
        $prototipo = Prototipo::findOrFail($id);

        // 🎯 Incrementar descarga
        $prototipo->increment('DESCARGA_PROTOTIPO');

        if (empty($prototipo->URL_PROTOTIPO)) {
            abort(404, 'Archivo no disponible');
        }

        $filePath = public_path(ltrim($prototipo->URL_PROTOTIPO, '/'));

        if (!file_exists($filePath)) {
            abort(404, 'Archivo no encontrado');
        }

        $fileName = Str::slug($prototipo->NOMBRE_PROTOTIPO) . '.pdf';

        return response()->download($filePath, $fileName);
    }

    /**
     * Almacena el prototipo en la base de datos.
     */
    public function store(Request $request)
    {
        try {
            $this->validateData($request);

            $prototipo = new Prototipo();
            $this->assignData($prototipo, $request);
            $prototipo->save();

            $this->assignFiles($prototipo, $request);

            // Guardar autores
            $autores = $this->handleAutores($request);
            $prototipo->autores()->sync($autores);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Prototipo creado correctamente.',
                    'prototipo' => $prototipo->load(['autores'])
                ]);
            }

            return redirect()->route('entities.dashboard.dashboard')->with('success', 'Prototipo registrado.');
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
                    'message' => 'Error al crear el prototipo: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('entities.dashboard.dashboard')->with('error', 'Error al crear el prototipo.');
        }
    }

    /**
     * Actualiza el prototipo en la base de datos.
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validateData($request, $id);

            $prototipo = Prototipo::findOrFail($id);
            $this->assignData($prototipo, $request);
            $prototipo->save();

            $this->assignFiles($prototipo, $request);

            // Guardar autores
            $autores = $this->handleAutores($request);
            $prototipo->autores()->sync($autores);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Prototipo actualizado.',
                    'prototipo' => $prototipo->load(['autores'])
                ]);
            }

            return redirect()->route('admin.prototipos.index')->with('success', 'Prototipo actualizado.');
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
                    'message' => 'Error al actualizar el prototipo: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('admin.prototipos.index')->with('error', 'Error al actualizar el prototipo.');
        }
    }

    /**
     * Elimina el prototipo de la base de datos.
     */
    public function destroy($id, Request $request)
    {
        try {
            $prototipo = Prototipo::findOrFail($id);
            $nombrePrototipo = $prototipo->NOMBRE_PROTOTIPO;

            // 🎯 Eliminar archivos asociados
            if ($prototipo->URL_PROTOTIPO) {
                $this->deleteFile($prototipo->URL_PROTOTIPO);
            }

            if ($prototipo->URL_IMAGEN_PROTOTIPO) {
                $this->deleteFile($prototipo->URL_IMAGEN_PROTOTIPO);
            }

            $prototipo->autores()->detach();
            $prototipo->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Prototipo '{$nombrePrototipo}' eliminado correctamente."
                ]);
            }

            return redirect()->route('admin.prototipos.index')
                ->with('success', "Prototipo '{$nombrePrototipo}' eliminado correctamente.");
        } catch (\Exception $e) {
            Log::error('Error al eliminar prototipo:', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error inesperado al eliminar el prototipo.'
                ], 500);
            }

            return redirect()->route('admin.prototipos.index')
                ->with('error', 'Error inesperado al eliminar el prototipo.');
        }
    }

    /**
     * Aplica los filtros a la consulta de prototipos.
     */
    private function applyFilters($query, Request $request)
    {
        Log::info('=== DEBUG FILTROS PROTOTIPOS ===');
        Log::info('Request completo:', $request->all());

        if ($request->has('search') && !is_null($request->input('search'))) {
            $search = $request->input('search');
            $searchTerms = explode(' ', $search);

            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->orWhere('NOMBRE_PROTOTIPO', 'like', "%$term%")
                        ->orWhere('PROPOSITO_PROTOTIPO', 'like', "%$term%")
                        ->orWhere('INSTITUCION_PROTOTIPO', 'like', "%$term%")
                        ->orWhere('DESCRIPCION_PROTOTIPO', 'like', "%$term%")
                        ->orWhere('OBJETIVO_PROTOTIPO', 'like', "%$term%")
                        ->orWhere('CARACTERISTICAS_PROTOTIPO', 'like', "%$term%")
                        ->orWhereHas('autores', function ($q) use ($term) {
                            $q->where('NOMBRE_AUTOR', 'like', "%$term%")
                                ->orWhere('APELLIDO_AUTOR', 'like', "%$term%");
                        });
                }
            });
        }

        $query = $this->applyYearFilters($query, $request, 'FECHA_PROTOTIPO', true);

        $ordenar = $request->input('ordenar', 'fecha_desc');
        switch ($ordenar) {
            case 'nombre_asc':
                $query->orderBy('NOMBRE_PROTOTIPO', 'asc');
                break;
            case 'nombre_desc':
                $query->orderBy('NOMBRE_PROTOTIPO', 'desc');
                break;
            case 'fecha_asc':
                $query->orderBy('FECHA_PROTOTIPO', 'asc');
                break;
            case 'fecha_desc':
                $query->orderBy('FECHA_PROTOTIPO', 'desc');
                break;
            case 'institucion_asc':
                $query->orderBy('INSTITUCION_PROTOTIPO', 'asc');
                break;
            case 'institucion_desc':
                $query->orderBy('INSTITUCION_PROTOTIPO', 'desc');
                break;
        }

        return $query;
    }

    /**
     * Valida que los datos del prototipo sean los correctos.
     */
    private function validateData(Request $request, $id = null)
    {
        $request->validate([
            'nombre_prototipo' => 'required|string|max:255',
            'proposito_prototipo' => 'required|string|max:255',
            'institucion_prototipo' => 'required|string|max:255',
            'descripcion_prototipo' => 'required|string',
            'objetivo_prototipo' => 'required|string',
            'caracteristicas_prototipo' => 'required|string',
            'fecha_prototipo' => 'required|date',
            'url_prototipo' => 'nullable|file|mimes:pdf,doc,docx',
            'url_imagen_prototipo' => 'nullable|file|mimes:png,jpg,jpeg,webp',
            'nombre_autores' => 'required|array',
            'apellido_autores' => 'required|array',
        ]);
    }

    /**
     * Asigna los datos del prototipo a la instancia del modelo.
     */
    private function assignData(Prototipo $prototipo, Request $request)
    {
        $prototipo->NOMBRE_PROTOTIPO = $request->nombre_prototipo;
        $prototipo->PROPOSITO_PROTOTIPO = $request->proposito_prototipo;
        $prototipo->INSTITUCION_PROTOTIPO = $request->institucion_prototipo;
        $prototipo->DESCRIPCION_PROTOTIPO = $request->descripcion_prototipo;
        $prototipo->OBJETIVO_PROTOTIPO = $request->objetivo_prototipo;
        $prototipo->CARACTERISTICAS_PROTOTIPO = $request->caracteristicas_prototipo;
        $prototipo->FECHA_PROTOTIPO = $request->fecha_prototipo;

        if (!$prototipo->exists) {
            $prototipo->VISTA_PROTOTIPO = 0;
            $prototipo->DESCARGA_PROTOTIPO = 0;
        }

        $prototipo->ID_USUARIO = Auth::id();
    }

    /**
     * Asigna los archivos del prototipo.
     */
    private function assignFiles(Prototipo $prototipo, Request $request)
    {
        $uploadedFiles = $this->handleMultipleFileUploadsSimple($request, 'prototipos', $prototipo->ID_PROTOTIPO, [
            'url_prototipo',
            'url_imagen_prototipo'
        ]);

        if (isset($uploadedFiles['url_prototipo'])) {
            // Eliminar archivo anterior
            if ($prototipo->URL_PROTOTIPO) {
                $this->deleteFile($prototipo->URL_PROTOTIPO);
            }
            $prototipo->URL_PROTOTIPO = $uploadedFiles['url_prototipo'];
        }

        if (isset($uploadedFiles['url_imagen_prototipo'])) {
            // Eliminar imagen anterior
            if ($prototipo->URL_IMAGEN_PROTOTIPO) {
                $this->deleteFile($prototipo->URL_IMAGEN_PROTOTIPO);
            }
            $prototipo->URL_IMAGEN_PROTOTIPO = $uploadedFiles['url_imagen_prototipo'];
        }

        // Guardar cambios de archivos
        if (!empty($uploadedFiles)) {
            $prototipo->save();
        }
    }
}