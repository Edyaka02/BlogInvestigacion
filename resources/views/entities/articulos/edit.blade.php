@extends('layouts.admin')

@section('title', 'Artículos')

@section('content')

    <div class="container-fluid mt-5 flex-grow-1 d-flex flex-column">
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-2 gap-2">
                    <form method="GET" {{-- action="{{ route('admin.articulos.index') }}"  --}} class="d-flex align-items-center gap-2">
                        <input type="text" name="q" class="form-control form-control-sm w-auto"
                            style="min-width:180px;" placeholder="Buscar..." value="{{ request('q') }}">
                        <button class="btn custom-button custom-button-ver" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                    <div class="d-flex gap-1">
                        <button type="button" class="btn custom-button custom-button-ver" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasFiltros" aria-controls="offcanvasFiltros">
                            <i class="fa-solid fa-filter"></i>
                            <span class="btn-text">Filtro</span>
                        </button>
                        @include('components.filtro')
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
                <div class="table-responsive">
                    <table class="table-custom align-middle w-100">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>ISSN</th>
                                <th>Fecha</th>
                                <th>Revista</th>
                                <th>Tipo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($articulos as $row)
                                <tr>
                                    <td>{{ $row->TITULO_ARTICULO }}</td>
                                    <td>{{ $row->ISSN_ARTICULO }}</td>
                                    <td>{{ $row->FECHA_ARTICULO }}</td>
                                    <td>{{ $row->REVISTA_ARTICULO }}</td>
                                    <td>{{ $row->tipo->NOMBRE_TIPO ?? '' }}</td>
                                    <td>

                                        <div class="d-flex">
                                            <div class="ms-2">
                                                <button type="button" class="btn custom-button custom-button-editar"
                                                    data-bs-toggle="modal" data-bs-target="#articuloModal"
                                                    data-id="{{ $row->ID_ARTICULO }}" data-issn="{{ $row->ISSN_ARTICULO }}"
                                                    data-titulo="{{ $row->TITULO_ARTICULO }}"
                                                    data-resumen="{{ $row->RESUMEN_ARTICULO }}"
                                                    data-fecha="{{ $row->FECHA_ARTICULO }}"
                                                    data-revista="{{ $row->REVISTA_ARTICULO }}"
                                                    data-tipo="{{ $row->ID_TIPO }}"
                                                    data-url-revista="{{ $row->URL_REVISTA_ARTICULO }}"
                                                    data-url-articulo="{{ $row->URL_ARTICULO }}"
                                                    data-url-imagen="{{ $row->URL_IMAGEN_ARTICULO }}"
                                                    data-nombres-autores="{{ $row->autores->pluck('NOMBRE_AUTOR')->implode(',') }}"
                                                    data-apellidos-autores="{{ $row->autores->pluck('APELLIDO_AUTOR')->implode(',') }}"
                                                    data-orden-autores="{{ $row->autores->pluck('pivot.ORDEN_AUTOR')->implode(',') }}">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                            </div>

                                            <div class="ms-2">
                                                <button type="button" class="btn custom-button custom-button-eliminar"
                                                    data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                                    data-id="{{ $row->ID_ARTICULO }}" data-type="artículo"
                                                    data-route="articulos">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    {{ $articulos->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('entities.articulos.modal')
    @include('components.modal-eliminar')

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
