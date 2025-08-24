@extends('layouts.admin')

@section('title', 'Artículos')

@section('content')
    <div class="container-fluid mt-5 flex-grow-1 d-flex flex-column">

        <div class="row mb-3">
            <div class="col-12">
                <h1 class="text-start fw-bold">Artículos</h1>
            </div>
        </div>

        <div class="tabla-container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-2 gap-2">
                        @include('components.buscador.buscador')

                        <div class="d-flex gap-1">
                            <button type="button" class="btn custom-button custom-button-ver" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasFiltros" aria-controls="offcanvasFiltros">
                                <i class="fa-solid fa-filter"></i>
                                <span class="btn-text">Filtro</span>
                            </button>

                            @include('components.filtro')
                            <!-- ✅ Incluir el componente filtro -->
                            {{-- @include('components.filtro', [
                                'tiposArticulos' => $tiposArticulos ?? [],
                                'years' => $years ?? [],
                            ]) --}}

                            <button type="button" class="btn custom-button custom-button-subir" data-bs-toggle="modal"
                                data-bs-target="#articuloModal">
                                <i class="fa-solid fa-upload"></i>
                                <span class="btn-text">Crear</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <div id="tabla-resultados">
                        {{-- Aquí se cargará la tabla completa con header desde JavaScript --}}
                    </div>
                    {{-- <div class="table-responsive">
                                <table class="table-custom align-middle w-100"> --}}
                    {{-- <thead>
                                        <tr>
                                            <th>Título</th>
                                            <th>ISSN</th>
                                            <th>Fecha</th>
                                            <th>Revista</th>
                                            <th>Tipo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead> --}}
                    {{-- <tbody id="tabla-resultados"> --}}
                    {{-- Aquí se cargarán los resultados de los artículos --}}
                    {{-- Los datos se cargarán automáticamente vía AJAX --}}
                    {{-- </tbody> --}}
                    {{-- </table>
                            </div> --}}
                    <div class="d-flex justify-content-end mt-3">
                        {{-- Aquí se cargarán los controles de paginación --}}
                    </div>
                </div>
            </div>
            @include('components.carga')
        </div>
    </div>
    @include('entities.articulos.modal')
    @include('components.modal-eliminar')

@endsection

@push('scripts')
    @vite(['resources/js/entities/articulos/edit.js'])
    {{-- @vite(['resources/js/entities/articulos/edit2.js']) --}}

    {{-- <script>
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
    </script> --}}
@endpush
