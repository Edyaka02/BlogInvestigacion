@extends('layouts.admin_layout')

@section('title', 'Artículos')

@section('content')
    <div class="container mt-5 flex-grow-1 d-flex flex-column">
        <h1 class="h-white mb-3 tracking-in-expand">Artículos</h1>

        {{-- Formulario de búsqueda y filtrado --}}
        @include('admin.components.buscador_filtrado')

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
                                        data-resumen="{{ $row->RESUMEN_ARTICULO }}" data-fecha="{{ $row->FECHA_ARTICULO }}"
                                        data-revista="{{ $row->REVISTA_ARTICULO }}" data-tipo="{{ $row->TIPO_ARTICULO }}"
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
        // function toggleOptions(event, id) {
        //     event.stopPropagation();
        //     const element = document.getElementById(id);
        //     if (element.style.display === 'none' || element.style.display === '') {
        //         element.style.display = 'block';
        //     } else {
        //         element.style.display = 'none';
        //     }
        // }
        document.addEventListener('DOMContentLoaded', function() {

            // const anioRadios = document.querySelectorAll('input[name="anio"]');
            // const intervaloAniosCheckbox = document.getElementById('intervaloAniosCheckbox');
            // const intervaloAnios = document.getElementById('intervaloAnios');

            // anioRadios.forEach(radio => {
            //     radio.addEventListener('change', function() {
            //         if (this.value === 'intervalo') {
            //             intervaloAnios.style.display = 'block';
            //         } else {
            //             intervaloAnios.style.display = 'none';
            //             intervaloAnios.querySelectorAll('input').forEach(input => input.value = '');
            //         }
            //     });
            // });

            // if (intervaloAniosCheckbox.checked) {
            //     intervaloAnios.style.display = 'block';
            // }
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
