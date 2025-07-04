@extends('layouts.admin_layout')

@section('title', 'Premios')

@section('content')
    <div class="container mt-5 flex-grow-1 d-flex flex-column">
        <h1 class="h-white mb-3 tracking-in-expand">Premios</h1>

        {{-- Formulario de búsqueda y filtrado --}}
        @include('components.buscador_filtrado')

        @if (!$hasResults)
            @component('components.no_resultados', ['entityName' => 'premios'])
            @endcomponent
        @else
            <div class="row flex-grow-1">
                @foreach ($premios as $row)
                    <div class="col-md-3 mb-3 card_busqueda">
                        <div class="card custom-card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $row->PROYECTO_PREMIO }}</h5>
                                <input type="hidden" name="id_premio_card" value="{{ $row->ID_PREMIO }}">

                                <div class="custom-button-group">
                                    <div class="btn-group mt-2" role="group" aria-label="Actions">
                                        <button type="button" class="btn custom-button-editar" data-bs-toggle="modal"
                                            data-bs-target="#premioModal" data-id="{{ $row->ID_PREMIO }}"
                                            data-proyecto="{{ $row->PROYECTO_PREMIO }}"
                                            data-resumen="{{ $row->RESUMEN_PREMIO }}"
                                            data-organismo="{{ $row->ORGANISMO_PREMIO }}"
                                            data-ambito="{{ $row->AMBITO_PREMIO }}"
                                            data-certificado="{{ $row->CERTIFICADO_PREMIO }}"
                                            data-url-imagen="{{ $row->URL_IMAGEN_PREMIO }}"
                                            data-nombres-autores="{{ $row->autores->pluck('NOMBRE_AUTOR')->implode(',') }}"
                                            data-apellidos-autores="{{ $row->autores->pluck('APELLIDO_AUTOR')->implode(',') }}"
                                            data-orden-autores="{{ $row->autores->pluck('pivot.ORDEN_AUTOR')->implode(',') }}">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </button>

                                        <button type="button" class="btn custom-button-eliminar" data-bs-toggle="modal"
                                            data-bs-target="#modalEliminar" data-id="{{ $row->ID_PREMIO }}"
                                            data-type="premio" data-route="premios">
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

        {{-- Modal para crear/editar premios --}}
        @include('entities.premios.modal', ['config' => $config])
        @include('components.modal-eliminar')

        <!-- Mostrar enlaces de paginación -->
        <div class="d-flex justify-content-center mt-auto">
            {{ $premios->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('premioModal').addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;

                var data = {
                    id_premio: button.getAttribute('data-id'),
                    proyecto_premio: button.getAttribute('data-proyecto'),
                    resumen_premio: button.getAttribute('data-resumen'),
                    organismo_premio: button.getAttribute('data-organismo'),
                    ambito_premio: button.getAttribute('data-ambito')
                };

                // console.log(data);

                var modal = this;
                var form = modal.querySelector('#premioForm');

                // Configurar formulario para editar
                configureFormForEdit(form, data.id_premio, 'premios');

                // Muestra los datos en el modal
                setModalData(modal, data);

                console.log(data.url_certificado_premio);

                // Mostrar nombre del archivo y la imagen
                window.updateFileDisplay('file-certificado_premio', button.getAttribute('data-certificado'),
                    'No se ha elegido un certificado');
                // window.updateFileDisplay('file-certificado_premio', button.getAttribute('data-certificado'),
                //     'No se ha elegido un artículo');
                window.updateFileDisplay('file-imagen_premio', button.getAttribute('data-url-imagen'),
                    'No se ha elegido una imagen');
            });
        });
    </script>
@endpush