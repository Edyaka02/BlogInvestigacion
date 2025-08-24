@extends('layouts.public')

@section('title', 'Eventos')

@section('content')

    <div class="container mt-5 fade-in">
        <div class="mb-4">
            <h1 style="color: var(--color-7)">Eventos</h1>
        </div>

        {{-- Formulario de búsqueda y filtrado --}}
        @include('components.buscador_axios')
        
        <div id="resultados">
                
            <div id="resultados">
                {{-- <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div> --}}
            </div>
        </div>

    </div>
    <!-- Paginación -->
    {{-- <div class="d-flex justify-content-center">
        {{ $eventos->links() }}
    </div> --}}

@endsection

@vite('resources/js/entities/eventos/index.js')
