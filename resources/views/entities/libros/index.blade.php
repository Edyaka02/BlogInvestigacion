@extends('layouts.layout')

@section('title', 'Libros')

@section('content')

    <div class="container mt-5 fade-in">
        <div class="mb-4">
            <h1>Libros</h1>
        </div>

        {{-- Formulario de búsqueda y filtrado --}}
        @include('components.buscador_filtrado')

        @if (!$hasResults)
            @component('components.no_resultados', ['entityName' => 'Libros'])
            @endcomponent
        @else
            <div class="row index">
                @foreach ($libros as $libro)
                    <div class="col-md-4 mb-4">
                        <a href="{{ route('libro.show', $libro->ID_LIBRO) }}" class="card-link">
                            <div class="card text-dark card-has-bg click-col"
                                style="background-image:url('{{ asset($libro->URL_IMAGEN_LIBRO) }}');">
                                <img class="card-img d-none" src="{{ asset($libro->URL_IMAGEN_LIBRO) }}"
                                    alt="Imagen del libro">
                                <div class="card-img-overlay d-flex flex-column">
                                    <div class="card-body">
                                        <small class="card-meta mb-2">{{ $libro->EDITORIAL_LIBRO }}</small>
                                        <h4 class="card-title text-white mt-0">
                                            {{ $libro->TITULO_LIBRO }}
                                        </h4>
                                        <div>
                                            <small class="card-meta-category mb-2"><i class="far fa-clock"></i>
                                                {{ $libro->YEAR_LIBRO }}</small>
                                        </div>
                                        <div>
                                            <small class="card-meta-category">ISBN:
                                                {{ $libro->ISBN_LIBRO }}</small>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="media">
                                            <div class="media-body">
                                                <h6 class="my-0 text-white d-block">Autor</h6>
                                                <small class="card-meta-category">
                                                    @foreach ($libro->autores as $autor)
                                                        {{ $autor->NOMBRE_AUTOR }} {{ $autor->APELLIDO_AUTOR }}@if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center">
        {{ $libros->links() }}
    </div>
@endsection
