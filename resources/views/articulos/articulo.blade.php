@extends('layouts.layout')

@section('title', 'Articulos')

@section('content')

    <div class="container mt-5 fade-in">
        <div class="mb-4">
            <h1 class="text-white">Artículos</h1>
        </div>

        {{-- Formulario de búsqueda y filtrado --}}
        @include('admin.components.buscador_filtrado')

        @if (!$hasResults)
            @component('admin.components.no_resultados', ['entityName' => 'artículos'])
            @endcomponent
        @else
            <div class="row index">
                @foreach ($articulos as $articulo)
                    <div class="col-md-4 mb-4">
                        <a href="{{ route('articulo.show', $articulo->ID_ARTICULO) }}" class="card-link">
                            <div class="card text-dark card-has-bg click-col"
                                style="background-image:url('{{ asset($articulo->URL_IMAGEN_ARTICULO) }}');">
                                <img class="card-img d-none" src="{{ asset($articulo->URL_IMAGEN_ARTICULO) }}"
                                    alt="Imagen del artículo">
                                <div class="card-img-overlay d-flex flex-column">
                                    <div class="card-body">
                                        <small class="card-meta mb-2">{{ $articulo->REVISTA_ARTICULO }}</small>
                                        <h4 class="card-title text-black mt-0">
                                            {{ $articulo->TITULO_ARTICULO }}
                                        </h4>
                                        <div>
                                            <small class="card-meta-category mb-2"><i class="far fa-clock"></i>
                                                {{ $articulo->FECHA_ARTICULO }}</small>
                                        </div>
                                        <div>
                                            <small class="card-meta-category">ISSN:
                                                {{ $articulo->ISSN_ARTICULO }}</small>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="media">
                                            <div class="media-body">
                                                <h6 class="my-0 text-white d-block">Autor</h6>
                                                <small class="card-meta-category">
                                                    @foreach ($articulo->autores as $autor)
                                                        {{ $autor->NOMBRE_AUTOR }} {{ $autor->APELLIDO_AUTOR }}@if (!$loop->last),
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
        {{ $articulos->links() }}
    </div>

@endsection
