{{-- resources/views/entities/proyectos/edit.blade.php --}}
@extends('components.admin.adminLayout')

@section('title', 'Proyectos')

@section('content')
    <div class="container-fluid mt-5 flex-grow-1 d-flex flex-column">

        <div class="row mb-3">
            <div class="col-12">
                <h1 class="text-start fw-bold">Proyectos de Investigación</h1>
            </div>
        </div>

        <div class="tabla-container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-2 gap-2">

                        @include('components.shared.search')

                        <div class="d-flex gap-1">

                            <button type="button" class="btn custom-button custom-button-ver" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasFiltros" aria-controls="offcanvasFiltros">
                                <i class="fa-solid fa-filter"></i>
                                <span class="btn-text">Filtro</span>
                            </button>

                            @include('components.shared.filter')

                            <button type="button" class="btn custom-button custom-button-subir" data-bs-toggle="modal"
                                data-bs-target="#proyectosModal">
                                <i class="fa-solid fa-upload"></i>
                                <span class="btn-text">Crear</span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">

                    <div id="data-results">
                        {{-- Aquí se cargará la tabla completa con header desde JavaScript --}}
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        {{-- Aquí se cargarán los controles de paginación --}}
                    </div>
                </div>
            </div>
            @include('components.shared.loading')
            @include('components.shared.emptyState')
            @include('components.shared.errorState')
        </div>
    </div>
    @include('entities.proyectos.modal')
    @include('components.admin.modalDelete')

@endsection

@push('scripts')
    @vite(['resources/js/entities/proyectos/edit.js'])
@endpush