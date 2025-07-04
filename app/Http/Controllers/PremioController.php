<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Premio;
use App\Models\Autor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Traits\Archivos;
use App\Traits\AutorTrait;
use App\Traits\OpcionesTrait;
use Illuminate\Support\Facades\Auth;

class PremioController extends Controller
{
    use Archivos, AutorTrait, OpcionesTrait;

    public function index(Request $request)
    {
        $query = Premio::with('autores')
            ->select('ID_PREMIO', 'AMBITO_PREMIO', 'ORGANISMO_PREMIO', 'PROYECTO_PREMIO', 'RESUMEN_PREMIO', 'URL_IMAGEN_PREMIO');

        $hasResults = $this->applyFilters($query, $request);

        $premios = $query->paginate(10)->appends($request->except('page'));

        $config = $this->getConfig(['ambitos', 'organismos']);

        $route = route('premios.index');

        foreach ($premios as $premio) {
            foreach ($premio->autores as $autor) {
                $this->splitAuthorName($autor);
            }
            $premio->URL_IMAGEN_PREMIO = Storage::url($premio->URL_IMAGEN_PREMIO);
        }

        return view('entities.premios.index', compact('premios', 'config', 'route', 'hasResults'));
    }

    public function adminIndex(Request $request)
    {
        $query = Premio::with('autores');
        $hasResults = $this->applyFilters($query, $request);

        $premios = $query->paginate(10)->appends($request->except('page'));

        $config = $this->getConfig(['ambitos', 'organismos']);

        $route = route('admin.premios.index');

        return view('entities.premios.edit', compact('premios', 'config', 'route', 'hasResults'));
    }

    public function store(Request $request)
    {
        $this->validatePremio($request);

        $premio = new Premio();
        $this->assignPremioData($premio, $request);

        $premio->save();

        $autores = $this->handleAutores($request);
        //$premio->autores()->sync($autores);
        Autor::syncAutores($premio, $autores);

        return redirect()->route('admin.dashboard')->with('success', 'Premio registrado.');
    }

    public function update(Request $request, $id)
    {
        $this->validatePremio($request, $id);

        $premio = Premio::findOrFail($id);
        $this->assignPremioData($premio, $request);

        $premio->save();

        $autores = $this->handleAutores($request);
        //$premio->autores()->sync($autores);
        Autor::syncAutores($premio, $autores);


        return redirect()->route('admin.premios.index')->with('success', 'Premio actualizado.');
    }

    public function destroy($id)
    {
        $premio = Premio::findOrFail($id);
        $premio->delete();

        return redirect()->route('admin.premios.index')->with('success', 'Premio eliminado.');
    }

    private function applyFilters($query, Request $request)
{
    // Filtro por búsqueda general (proyecto, organismo, autores)
    if ($request->has('search') && !is_null($request->input('search'))) {
        $search = $request->input('search');
        $searchTerms = explode(' ', $search); // Divide el término de búsqueda en palabras

        $query->where(function ($q) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $q->orWhere('PROYECTO_PREMIO', 'like', "%$term%")
                    ->orWhere('ORGANISMO_PREMIO', 'like', "%$term%")
                    ->orWhereHas('autores', function ($q) use ($term) {
                        $q->where('NOMBRE_AUTOR', 'like', "%$term%")
                            ->orWhere('APELLIDO_AUTOR', 'like', "%$term%");
                    });
            }
        });
    }

    // Filtro por ámbito
    if ($request->has('ambito') && !empty($request->input('ambito'))) {
        $query->where('AMBITO_PREMIO', $request->input('ambito'));
    }

    // Filtro por organismo
    if ($request->has('organismo') && !empty($request->input('organismo'))) {
        $query->where('ORGANISMO_PREMIO', $request->input('organismo'));
    }

    // Filtro por rango de fechas (si existe un campo de fecha en la tabla)
    if ($request->has('fecha_inicio') && $request->has('fecha_fin') &&
        !is_null($request->input('fecha_inicio')) && !is_null($request->input('fecha_fin'))) {
        $query->whereBetween('created_at', [$request->input('fecha_inicio'), $request->input('fecha_fin')]);
    }

    // Ordenar resultados
    $ordenar = $request->input('ordenar', 'proyecto_asc'); // Establecer 'proyecto_asc' como predeterminado
    switch ($ordenar) {
        case 'proyecto_asc':
            $query->orderBy('PROYECTO_PREMIO', 'asc');
            break;
        case 'proyecto_desc':
            $query->orderBy('PROYECTO_PREMIO', 'desc');
            break;
        case 'ambito_asc':
            $query->orderBy('AMBITO_PREMIO', 'asc');
            break;
        case 'ambito_desc':
            $query->orderBy('AMBITO_PREMIO', 'desc');
            break;
    }

    return $query->exists();
}

    private function validatePremio(Request $request, $id = null)
    {
        $request->validate([
            'ambito_premio' => 'required|string|max:30',
            'proyecto_premio' => 'required|string|max:150',
            'resumen_premio' => 'required|string',
            'organismo_premio' => 'nullable|string|max:150',
            'url_certificado_premio' => 'nullable|file|mimes:pdf',
            'url_imagen_premio' => 'nullable|file|mimes:png,jpg,jpeg,webp',
            'nombre_autores' => 'required|array',
            'apellido_autores' => 'required|array',
        ]);
    }

    private function assignPremioData(Premio $premio, Request $request)
    {
        $premio->AMBITO_PREMIO = $request->ambito_premio;
        $premio->ORGANISMO_PREMIO = $request->organismo_premio;
        $premio->PROYECTO_PREMIO = $request->proyecto_premio;
        $premio->RESUMEN_PREMIO = $request->resumen_premio;

        $premio->ID_USUARIO = Auth::id();

        if ($request->hasFile('url_certificado_premio')) {
            $premio->CERTIFICADO_PREMIO = $this->handleFileUpload($request, 'url_certificado_premio', 'certificados');
        }

        if ($request->hasFile('url_imagen_premio')) {
            $premio->URL_IMAGEN_PREMIO = $this->handleFileUpload($request, 'url_imagen_premio', 'imagenes');
        }
    }
}