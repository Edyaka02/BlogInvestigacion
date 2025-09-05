{{-- resources/views/entities/proyectos/index.blade.php --}}
@extends('components.public.publicLayout')

@section('title', 'Proyectos de Investigación')

@section('content')
    <div class="container-fluid mt-5 flex-grow-1 d-flex flex-column">

        <div class="row mb-3">
            <div class="col-12">
                <h1 class="text-start">Proyectos de Investigación</h1>
                <p class="lead text-muted">Explora nuestros proyectos de investigación financiados por diversos organismos</p>
            </div>
        </div>

        <div class="tabla-container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-2 gap-2">

                        @include('components.shared.search')

                        <div class="d-flex gap-1">

                            <button type="button" class="btn custom-button" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasFiltros" aria-controls="offcanvasFiltros">
                                <i class="fa-solid fa-filter"></i>
                                <span class="btn-text">Filtro</span>
                            </button>

                            @include('components.shared.filter')
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">

                    <div id="data-results">
                        {{-- Aquí se cargará la grilla de cards con proyectos desde JavaScript --}}
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

@endsection

@push('scripts')
    @vite(['resources/js/entities/proyectos/index.js'])
@endpush