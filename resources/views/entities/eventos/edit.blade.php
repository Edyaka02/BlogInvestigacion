@extends('layouts.admin_layout')

@section('title', 'Eventos')

@section('content')
    <div class="container mt-5 flex-grow-1 d-flex flex-column">
        <h1 class="h-white mb-3 tracking-in-expand">Eventos</h1>

        {{-- Formulario de búsqueda y filtrado --}}
        @include('components.buscador_filtrado')
        @if (!$hasResults)
            @component('components.no_resultados', ['entityName' => 'eventos'])
            @endcomponent
        @else
            <div class="row flex-grow-1">
                @foreach ($eventos as $row)
                    <div class="col-md-3 mb-3 card_busqueda">
                        <div class="card custom-card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $row->TITULO_EVENTO }}</h5>
                                <input type="hidden" name="id_evento_card" value="{{ $row->ID_EVENTO }}">

                                <div class="custom-button-group">
                                    <div class="btn-group mt-2" role="group" aria-label="Actions">
                                        <button type="button" class="btn custom-button-editar" data-bs-toggle="modal"
                                            data-bs-target="#eventoModal" data-id="{{ $row->ID_EVENTO }}"
                                            data-tipo="{{ $row->TIPO_EVENTO }}" data-titulo="{{ $row->TITULO_EVENTO }}"
                                            data-resumen="{{ $row->RESUMEN_EVENTO }}"
                                            data-nombre="{{ $row->NOMBRE_EVENTO }}"
                                            data-modalidad="{{ $row->MODALIDAD_EVENTO }}"
                                            data-comunicacion="{{ $row->COMUNICACION_EVENTO }}"
                                            data-ambito="{{ $row->AMBITO_EVENTO }}"
                                            data-eje-tematico="{{ $row->EJE_TEMATICO_EVENTO }}"
                                            data-url-evento="{{ $row->URL_EVENTO }}"
                                            data-url-imagen="{{ $row->URL_IMAGEN_EVENTO }}"
                                            data-nombres-autores="{{ $row->autores->pluck('NOMBRE_AUTOR')->implode(',') }}"
                                            data-apellidos-autores="{{ $row->autores->pluck('APELLIDO_AUTOR')->implode(',') }}"
                                            data-orden-autores="{{ $row->autores->pluck('pivot.ORDEN_AUTOR')->implode(',') }}">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </button>

                                        <button type="button" class="btn custom-button-eliminar" data-bs-toggle="modal"
                                            data-bs-target="#modalEliminar" data-id="{{ $row->ID_EVENTO }}"
                                            data-type="evento" data-route="eventos">
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

        @include('entities.eventos.modal', ['config' => $config])
        @include('components.modal-eliminar')

        <!-- Mostrar enlaces de paginación -->
        <div class="d-flex justify-content-center mt-auto">
            {{ $eventos->links() }}
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('eventoModal').addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;

                var data = {
                    id_evento: button.getAttribute('data-id'),
                    tipo_evento: button.getAttribute('data-tipo'),
                    titulo_evento: button.getAttribute('data-titulo'),
                    resumen_evento: button.getAttribute('data-resumen'),
                    nombre_evento: button.getAttribute('data-nombre'),
                    modalidad_evento: button.getAttribute('data-modalidad'),
                    comunicacion_evento: button.getAttribute('data-comunicacion'),
                    ambito_evento: button.getAttribute('data-ambito'),
                    eje_tematico_evento: button.getAttribute('data-eje-tematico'),
                    url_evento: button.getAttribute('data-url-evento')
                };

                var modal = this;
                var form = modal.querySelector('#eventoForm');

                // Configurar formulario para editar
                configureFormForEdit(form, data.id_evento, 'eventos');

                // Muestra los datos en el modal
                setModalData(modal, data);

                // Mostrar nombre de la imagen
                window.updateFileDisplay('file-imagen-evento', button.getAttribute('data-url-imagen'),
                    'No se ha elegido una imagen');
            });
        });
    </script>
@endpush