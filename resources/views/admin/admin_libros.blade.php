@extends('layouts.admin_layout')

@section('content')
    <div class="container mt-5 flex-grow-1 d-flex flex-column">
        <h1 class="h-white mb-3 tracking-in-expand">Libros</h1>

        {{-- Formulario de búsqueda y filtrado --}}
        @include('admin.components.buscador_filtrado')

        <div class="row flex-grow-1">
            @foreach ($libros as $row)
                <div class="col-md-3 mb-3 card_busqueda">
                    <div class="card custom-card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $row->TITULO_LIBRO }}</h5>
                            <input type="hidden" name="id_libro_card" value="{{ $row->ID_LIBRO }}">

                            <div class="custom-button-group">
                                <div class="btn-group mt-2" role="group" aria-label="Actions">
                                    <button type="button" class="btn custom-button-editar" data-bs-toggle="modal"
                                        data-bs-target="#libroModal" data-id="{{ $row->ID_LIBRO }}"
                                        data-titulo="{{ $row->TITULO_LIBRO }}" data-capitulo="{{ $row->CAPITULO_LIBRO }}"
                                        data-isbn="{{ $row->ISBN_LIBRO }}" data-year="{{ $row->YEAR_LIBRO }}"
                                        data-editorial="{{ $row->EDITORIAL_LIBRO }}" data-doi="{{ $row->DOI_LIBRO }}"
                                        data-url-libro="{{ $row->URL_LIBRO }}"
                                        data-url-imagen="{{ $row->URL_IMAGEN_LIBRO }}"
                                        data-nombres-autores="{{ $row->autores->pluck('NOMBRE_AUTOR')->implode(',') }}"
                                        data-apellidos-autores="{{ $row->autores->pluck('APELLIDO_AUTOR')->implode(',') }}"
                                        data-orden-autores="{{ $row->autores->pluck('pivot.ORDEN_AUTOR')->implode(',') }}">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </button>

                                    <button type="button" class="btn custom-button-eliminar" data-bs-toggle="modal"
                                        data-bs-target="#modalEliminar" data-id="{{ $row->ID_LIBRO }}" data-type="libro">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @include('admin.modals.modal_libro')
        @include('admin.modals.modal_eliminar')

        <!-- Mostrar enlaces de paginación -->
        <div class="d-flex justify-content-center mt-auto">
            {{ $libros->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('libroModal').addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var titulo = button.getAttribute('data-titulo');
                var capitulo = button.getAttribute('data-capitulo');
                var isbn = button.getAttribute('data-isbn');
                var year = button.getAttribute('data-year');
                var editorial = button.getAttribute('data-editorial');
                var doi = button.getAttribute('data-doi');
                var url_libro = button.getAttribute('data-url-libro');
                var url_imagen = button.getAttribute('data-url-imagen');

                var modal = this;
                var form = modal.querySelector('#libroForm');
                if (id) {
                    form.action = `/admin/libros/${id}`;
                    form.insertAdjacentHTML('beforeend',
                        '<input type="hidden" name="_method" value="PUT">');
                }

                modal.querySelector('#id_libro').value = id;
                modal.querySelector('#titulo_libro').value = titulo;
                modal.querySelector('#capitulo_libro').value = capitulo;
                modal.querySelector('#isbn_libro').value = isbn;
                modal.querySelector('#year_libro').value = year;
                modal.querySelector('#editorial_libro').value = editorial;
                modal.querySelector('#doi_libro').value = doi;

                // // Mostrar nombre del archivo y la imagen
                // document.getElementById('file-libro').innerHTML =
                //     `<span>${url_libro.split('/').pop()}</span>`;
                // document.getElementById('file-imagen').innerHTML =
                //     `<span>${url_imagen.split('/').pop()}</span>`;

                // Mostrar nombre del archivo y la imagen
                document.getElementById('file-libro').innerHTML =
                    url_libro ? `<span>${url_libro.split('/').pop()}</span>` :
                    '<span>No se ha elegido un libro</span>';
                document.getElementById('file-imagen_libro').innerHTML =
                    url_imagen ? `<span>${url_imagen.split('/').pop()}</span>` :
                    '<span>No se ha elegido una imagen</span>';

            });
        });
    </script>
@endpush
