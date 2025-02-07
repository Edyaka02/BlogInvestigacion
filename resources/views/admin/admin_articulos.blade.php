@extends('layouts.admin_layout')

@section('title', 'Artículos')

@section('content')
    <div class="container mt-5 flex-grow-1 d-flex flex-column">
        <h1 class="h-white mb-3 tracking-in-expand">Artículos</h1>

        {{-- Formulario de búsqueda y filtrado --}}
        @include('admin.components.buscador_filtrado')
        @if (!$hasResults)
            <div class="text-center mt-5">
                <img src="{{ asset('img/pagina-no-encontrada.png') }}" alt="No results found" class="img-fluid mb-4"
                    style="max-width: 300px;">
                <h2 class="text-muted">No se encontraron artículos</h2>
                <p class="text-muted">Intenta ajustar tus criterios de búsqueda.</p>
            </div>
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
        @endif

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

                var modal = this;
                var form = modal.querySelector('#articuloForm');
                if (id) {
                    form.action = `/admin/articulos/${id}`;
                    form.insertAdjacentHTML('beforeend',
                        '<input type="hidden" name="_method" value="PUT">');
                }

                modal.querySelector('#id_articulo').value = id;
                modal.querySelector('#issn_articulo').value = issn;
                modal.querySelector('#titulo_articulo').value = titulo;
                modal.querySelector('#resumen_articulo').value = resumen;
                modal.querySelector('#fecha_articulo').value = fecha;
                modal.querySelector('#revista_articulo').value = revista;
                modal.querySelector('#tipo_articulo').value = tipo;
                modal.querySelector('#url_revista_articulo').value = url_revista;

                // Mostrar nombre del archivo y la imagen
                document.getElementById('file-articulo').innerHTML =
                    url_articulo ? `<span>${url_articulo.split('/').pop()}</span>` :
                    '<span>No se ha elegido un artículo</span>';
                document.getElementById('file-imagen').innerHTML =
                    url_imagen ? `<span>${url_imagen.split('/').pop()}</span>` :
                    '<span>No se ha elegido una imagen</span>';
            });
        });
    </script>
@endpush
