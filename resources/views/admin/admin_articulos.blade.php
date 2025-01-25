@extends('layouts.admin_layout')

@section('title', 'Artículos')

@section('content')
    <div class="container mt-5 flex-grow-1 d-flex flex-column">
        <h1 class="h-white mb-3 tracking-in-expand">Artículos</h1>

        {{-- @include('admin.components.search.barraBusqueda') --}}

        {{-- Formulario de búsqueda --}}
        {{-- <form method="GET" action="{{ route('admin.articulos.index') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Buscar artículos..." value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form> --}}

        {{-- Formulario de búsqueda y filtrado --}}
        @include('admin.components.buscador_filtrado')

        {{-- Formulario de búsqueda --}}
        {{-- <form method="GET" action="{{ route('admin.articulos.index') }}" class="mb-4">
            <div class="row">
                <div class="col-md-9">
                    <div class="input-group mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Buscar artículos..."
                            value="{{ request()->query('search') }}">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dropend mb-3">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Filtrar
                        </button>
                        <div class="dropdown-menu p-4" aria-labelledby="dropdownMenuButton"
                            style="max-height: 300px; overflow-y: auto;">
                            <div class="mb-3">
                                <label class="form-label">Filtrar por tipo de revista</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tipo[]" value="Investigacion"
                                        id="tipoInvestigacion"
                                        {{ in_array('Investigacion', request()->query('tipo', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tipoInvestigacion">
                                        Investigación
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tipo[]" value="Divulgacion"
                                        id="tipoDivulgacion"
                                        {{ in_array('Divulgacion', request()->query('tipo', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tipoDivulgacion">
                                        Divulgación
                                    </label>
                                </div>
                            </div>
                            @if (!empty($years))
                                <div class="mb-3">
                                    <label class="form-label">Filtrar por año</label>
                                    @foreach ($years as $year)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="anio[]"
                                                value="{{ $year }}" id="anio{{ $year }}"
                                                {{ in_array($year, request()->query('anio', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="anio{{ $year }}">
                                                {{ $year }}
                                            </label>
                                        </div>
                                    @endforeach
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="intervaloAniosCheckbox">
                                        <label class="form-check-label" for="intervaloAniosCheckbox">
                                            Intervalo de años
                                        </label>
                                    </div>
                                    <div id="intervaloAnios" class="mt-2" style="display: none;">
                                        <div class="input-group input-group-sm">
                                            <input type="number" name="anio_inicio" class="form-control"
                                                placeholder="Inicio" value="{{ request()->query('anio_inicio') }}"
                                                style="max-width: 80px;">
                                            <span class="input-group-text">-</span>
                                            <input type="number" name="anio_fin" class="form-control" placeholder="Fin"
                                                value="{{ request()->query('anio_fin') }}" style="max-width: 80px;">
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label">Ordenar por</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ordenar" value="titulo_asc"
                                        id="ordenarTituloAsc"
                                        {{ request()->query('ordenar') == 'titulo_asc' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ordenarTituloAsc">
                                        Título (A-Z)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ordenar" value="titulo_desc"
                                        id="ordenarTituloDesc"
                                        {{ request()->query('ordenar') == 'titulo_desc' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ordenarTituloDesc">
                                        Título (Z-A)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ordenar" value="fecha_asc"
                                        id="ordenarFechaAsc"
                                        {{ request()->query('ordenar') == 'fecha_asc' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ordenarFechaAsc">
                                        Fecha (Ascendente)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ordenar" value="fecha_desc"
                                        id="ordenarFechaDesc"
                                        {{ request()->query('ordenar') == 'fecha_desc' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ordenarFechaDesc">
                                        Fecha (Descendente)
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form> --}}

        <div class="row flex-grow-1">
            @foreach ($articulos as $row)
                <div class="col-md-3 mb-3 card_busqueda">
                    <div class="card custom-card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $row->TITULO_ARTICULO }}</h5>
                            <input type="hidden" name="id_articulo_card" value="{{ $row->ID_ARTICULO }}">

                            <div class="custom-button-group">
                                <div class="btn-group mt-2" role="group" aria-label="Actions">
                                    <button type="button" class="btn custom-button-editar" data-bs-toggle="modal"
                                        data-bs-target="#articuloModal" data-id="{{ $row->ID_ARTICULO }}"
                                        data-issn="{{ $row->ISSN_ARTICULO }}" data-titulo="{{ $row->TITULO_ARTICULO }}"
                                        data-resumen="{{ $row->RESUMEN_ARTICULO }}"
                                        data-fecha="{{ $row->FECHA_ARTICULO }}"
                                        data-revista="{{ $row->REVISTA_ARTICULO }}"
                                        data-tipo="{{ $row->TIPO_ARTICULO }}"
                                        data-url-revista="{{ $row->URL_REVISTA_ARTICULO }}"
                                        data-url-articulo="{{ $row->URL_ARTICULO }}"
                                        data-url-imagen="{{ $row->URL_IMAGEN_ARTICULO }}"
                                        data-nombres-autores="{{ $row->autores->pluck('NOMBRE_AUTOR')->implode(',') }}"
                                        data-apellidos-autores="{{ $row->autores->pluck('APELLIDO_AUTOR')->implode(',') }}"
                                        data-orden-autores="{{ $row->autores->pluck('pivot.ORDEN_AUTOR')->implode(',') }}">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </button>

                                    <button type="button" class="btn custom-button-eliminar" data-bs-toggle="modal"
                                        data-bs-target="#modalEliminar" data-id="{{ $row->ID_ARTICULO }}"
                                        data-type="artículo">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- @include('admin.modals.modal_articulo', ['accion' => $accion]) --}}
        @include('admin.modals.modal_articulo', ['tiposArticulos' => $tiposArticulos])
        @include('admin.modals.modal_eliminar')

        <!-- Mostrar enlaces de paginación -->
        <div class="d-flex justify-content-center mt-auto">
            {{ $articulos->links() }}
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const intervaloAniosCheckbox = document.getElementById('intervaloAniosCheckbox');
            const intervaloAnios = document.getElementById('intervaloAnios');

            intervaloAniosCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    intervaloAnios.style.display = 'block';
                } else {
                    intervaloAnios.style.display = 'none';
                }
            });
            document.getElementById('articuloModal').addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var issn = button.getAttribute('data-issn');
                var titulo = button.getAttribute('data-titulo');
                var resumen = button.getAttribute('data-resumen');
                var fecha = button.getAttribute('data-fecha');
                var revista = button.getAttribute('data-revista');
                var tipo = button.getAttribute('data-tipo');
                var url_revista = button.getAttribute('data-url-revista');
                var url_articulo = button.getAttribute('data-url-articulo');
                var url_imagen = button.getAttribute('data-url-imagen');

                document.getElementById('id_articulo').value = id;
                document.getElementById('issn_articulo').value = issn;
                document.getElementById('titulo_articulo').value = titulo;
                document.getElementById('resumen_articulo').value = resumen;
                document.getElementById('fecha_articulo').value = fecha;
                document.getElementById('revista_articulo').value = revista;
                document.getElementById('tipo_articulo').value = tipo;
                document.getElementById('url_revista_articulo').value = url_revista;

                // Mostrar nombre del archivo y la imagen
                document.getElementById('file-articulo').innerHTML =
                    `<span>${url_articulo.split('/').pop()}</span>`;
                document.getElementById('file-imagen').innerHTML =
                    `<span>${url_imagen.split('/').pop()}</span>`;
            });
        });
    </script>
@endpush
