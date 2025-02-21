<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Autor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Traits\Archivos;
use App\Traits\AutorTrait;
use App\Traits\YearTrait;

class EventoController extends Controller
{
    use Archivos, AutorTrait, YearTrait;

    public function index(Request $request)
    {
        $query = Evento::with('autores')
            ->where('ELIMINADO_EVENTO', false)
            ->select('ID_EVENTO', 'TIPO_EVENTO', 'TITULO_EVENTO', 'NOMBRE_EVENTO', 'URL_IMAGEN_EVENTO');

        $hasResults = $this->applyFilters($query, $request);
        // $years = $this->applyYears(2);

        $eventos = $query->paginate(10)->appends($request->except('page'));

        $tiposEventos = config('tipos.eventos');

        $route = route('eventos.evento');

        foreach ($eventos as $evento) {
            foreach ($evento->autores as $autor) {
                $this->splitAuthorName($autor);
            }
            $evento->URL_IMAGEN_EVENTO = Storage::url($evento->URL_IMAGEN_EVENTO);
        }

        return view('eventos.evento', compact('eventos', 'tiposEventos', 'route', 'hasResults'));
    }

    public function adminIndex(Request $request)
    {
        $query = Evento::with('autores')->where('ELIMINADO_EVENTO', false);
        $hasResults = $this->applyFilters($query, $request);
        // $years = $this->applyYears(2);

        $eventos = $query->paginate(10)->appends($request->except('page'));

        $config = [
            'tiposEventos' => config('tipos.eventos'),
            'ambitos' => config('tipos.ambitos'),
            'modalidades' => config('tipos.modalidad'),
            'comunicacion' => config('tipos.comunicacion')
        ];

        $route = route('admin.eventos.index');

        return view('admin.admin_eventos', compact('eventos', 'config', 'route', 'hasResults'));
    }

    public function show($id)
    {
        $evento = Evento::with('autores')->findOrFail($id);

        $evento->URL_EVENTO = Storage::url($evento->URL_EVENTO);
        $evento->URL_IMAGEN_EVENTO = Storage::url($evento->URL_IMAGEN_EVENTO);

        return view('eventos.evento_detalle', compact('evento'));
    }

    public function create()
    {
        $config = [
            'tiposEventos' => config('tipos.eventos'),
            'ambitos' => config('tipos.ambitos'),
            'modalidades' => config('tipos.modalidad'),
            'comunicacion' => config('tipos.comunicacion')
        ];
        $autores = Autor::all();
        return view('admin.modals.modal_evento', compact('autores', 'config'));
    }

    public function store(Request $request)
    {
        $this->validateEvento($request);

        $evento = new Evento();
        $this->assignEventoData($evento, $request);
        $evento->ELIMINADO_EVENTO = false;

        $evento->save();

        $autores = $this->handleAutores($request);
        $evento->autores()->sync($autores);

        return redirect()->route('admin.dashboard')->with('success', 'Evento registrado.');
    }

    public function update(Request $request, $id)
    {
        $this->validateEvento($request, $id);

        $evento = Evento::findOrFail($id);
        $this->assignEventoData($evento, $request);

        $evento->save();

        $autores = $this->handleAutores($request);
        $evento->autores()->sync($autores);

        return redirect()->route('admin.eventos.index')->with('success', 'Evento actualizado.');
    }

    public function destroy($id)
    {
        $evento = Evento::findOrFail($id);
        $evento->ELIMINADO_EVENTO = true;
        $evento->save();

        return redirect()->route('admin.eventos.index')->with('success', 'Evento eliminado.');
    }

    public function restore($id)
    {
        $evento = Evento::findOrFail($id);

        if ($evento->ELIMINADO_EVENTO && $evento->updated_at->gt(now()->subWeek())) {
            $evento->ELIMINADO_EVENTO = false;
            $evento->save();

            return redirect()->route('admin.eventos.index')->with('success', 'Evento restaurado exitosamente.');
        }

        return redirect()->route('admin.eventos.index')->with('error', 'No se puede restaurar el evento.');
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->has('search') && !is_null($request->input('search'))) {
            $search = $request->input('search');
            $searchTerms = explode(' ', $search);

            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->orWhere('TITULO_EVENTO', 'like', "%$term%")
                        ->orWhere('NOMBRE_EVENTO', 'like', "%$term%")
                        ->orWhereHas('autores', function ($q) use ($term) {
                            $q->where('NOMBRE_AUTOR', 'like', "%$term%")
                                ->orWhere('APELLIDO_AUTOR', 'like', "%$term%");
                        });
                }
            });
        }

        if ($request->has('tipo') && !is_null($request->input('tipo'))) {
            $query->whereIn('TIPO_EVENTO', $request->input('tipo'));
        }
    
        if ($request->has('modalidad') && !is_null($request->input('modalidad'))) {
            $query->whereIn('MODALIDAD_EVENTO', $request->input('modalidad'));
        }
    
        if ($request->has('comunicacion') && !is_null($request->input('comunicacion'))) {
            $query->whereIn('COMUNICACION_EVENTO', $request->input('comunicacion'));
        }
    
        if ($request->has('ambito') && !is_null($request->input('ambito'))) {
            $query->whereIn('AMBITO_EVENTO', $request->input('ambito'));
        }    

        // $query = $this->applyYearFilters($query, $request, 'FECHA_EVENTO', true);

        $ordenar = $request->input('ordenar', 'fecha_desc');
        switch ($ordenar) {
            case 'titulo_asc':
                $query->orderBy('TITULO_EVENTO', 'asc');
                break;
            case 'titulo_desc':
                $query->orderBy('TITULO_EVENTO', 'desc');
                break;
            // case 'fecha_asc':
            //     $query->orderBy('FECHA_EVENTO', 'asc');
            //     break;
            // case 'fecha_desc':
            //     $query->orderBy('FECHA_EVENTO', 'desc');
            //     break;
        }

        return $query->exists();
    }

    private function validateEvento(Request $request, $id = null)
    {
        $request->validate([
            'tipo_evento' => 'required|string|max:30',
            'titulo_evento' => 'required|string|max:150',
            'resumen_evento' => 'required|string',
            'nombre_evento' => 'required|string|max:150',
            'modalidad_evento' => 'required|string|max:50',
            'comunicacion_evento' => 'required|string|max:30',
            'ambito_evento' => 'required|string|max:30',
            'eje_tematico_evento' => 'required|string|max:50',
            'url_evento' => 'nullable|url',
            'url_imagen_evento' => 'nullable|file|mimes:png,jpg,jpeg,webp',
            'nombre_autores' => 'required|array',
            'apellido_autores' => 'required|array',
        ]);
    }

    private function assignEventoData(Evento $evento, Request $request)
    {
        $evento->TIPO_EVENTO = $request->tipo_evento;
        $evento->TITULO_EVENTO = $request->titulo_evento;
        $evento->RESUMEN_EVENTO = $request->resumen_evento;
        $evento->NOMBRE_EVENTO = $request->nombre_evento;
        $evento->MODALIDAD_EVENTO = $request->modalidad_evento;
        $evento->COMUNICACION_EVENTO = $request->comunicacion_evento;
        $evento->AMBITO_EVENTO = $request->ambito_evento;
        $evento->EJE_TEMATICO_EVENTO = $request->eje_tematico_evento;
        $evento->URL_EVENTO = $request->url_evento;

        if ($request->hasFile('url_imagen_evento')) {
            $evento->URL_IMAGEN_EVENTO = $this->handleFileUpload($request, 'url_imagen_evento', 'imagenes');
        }
    }
}