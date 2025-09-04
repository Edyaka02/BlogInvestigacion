@extends('components.public.publicLayout')

@section('title', 'Artículos')

@section('content')
    <div class="container-fluid mt-5 flex-grow-1 d-flex flex-column">

        <div class="row mb-3">
            <div class="col-12">
                <h1 class="text-start">Artículos</h1>
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

@endsection

@push('scripts')
    @vite(['resources/js/entities/articulos/index.js'])
@endpush
