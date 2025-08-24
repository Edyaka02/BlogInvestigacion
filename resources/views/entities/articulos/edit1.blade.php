@extends('layouts.admin')

@section('title', 'Artículos')

@section('content')
    <div class="container mt-5 flex-grow-1 d-flex flex-column">
        <h1 class="h-white mb-3 tracking-in-expand">Artículos es edit</h1>

        {{-- Formulario de búsqueda y filtrado --}}
        {{-- @include('components.buscador_filtrado') --}}
        <div class="w-100">
            {{-- @include('components.buscador_filtrado') --}}
            @include('components.buscador')
        </div>

        @if (!$hasResults)
            @component('components.no_resultados', ['entityName' => 'artículos'])
            @endcomponent
        @else
            <div class="row flex-grow-1">
                @foreach ($articulos as $row)
                    <div class="col-md-3 mb-3 card_busqueda">
                        <div class="card custom-card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $row->TITULO_ARTICULO }}</h5>
                                <input type="hidden" name="id_articulo_card" value="{{ $row->ID_ARTICULO }}">

                                <div class="custom-button-group">
                                    <div class="btn-group mt-2" role="group" aria-label="Actions">
                                        <button type="button" class="btn custom-button custom-button-editar"
                                            data-bs-toggle="modal" data-bs-target="#articuloModal"
                                            data-id="{{ $row->ID_ARTICULO }}" data-issn="{{ $row->ISSN_ARTICULO }}"
                                            data-titulo="{{ $row->TITULO_ARTICULO }}"
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
                                            <i class="fa-solid fa-pen-to-square"></i> Editar
                                        </button>

                                        <button type="button" class="btn custom-button custom-button-eliminar"
                                            data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                            data-id="{{ $row->ID_ARTICULO }}" data-type="artículo" data-route="articulos">
                                            <i class="fa-solid fa-trash-can"></i> Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @include('entities.articulos.modal')
        @include('components.modal-eliminar')

        <!-- Mostrar enlaces de paginación -->
        <div class="d-flex justify-content-center mt-auto">
            {{ $articulos->links() }}
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('articuloModal').addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;

                var data = {
                    id_articulo: button.getAttribute('data-id'),
                    issn_articulo: button.getAttribute('data-issn'),
                    titulo_articulo: button.getAttribute('data-titulo'),
                    resumen_articulo: button.getAttribute('data-resumen'),
                    fecha_articulo: button.getAttribute('data-fecha'),
                    revista_articulo: button.getAttribute('data-revista'),
                    tipo_articulo: button.getAttribute('data-tipo'),
                    url_revista_articulo: button.getAttribute('data-url-revista')
                };

                var modal = this;
                var form = modal.querySelector('#articuloForm');

                // Configurar formulario para editar
                configureFormForEdit(form, data.id_articulo, 'articulos');

                // Muestra los datos en el modal
                setModalData(modal, data);

                // Mostrar nombre del archivo y la imagen
                window.updateFileDisplay('file-articulo', button.getAttribute('data-url-articulo'),
                    'No se ha elegido un artículo');
                window.updateFileDisplay('file-imagen', button.getAttribute('data-url-imagen'),
                    'No se ha elegido una imagen');
            });
        });
    </script>
@endpush
