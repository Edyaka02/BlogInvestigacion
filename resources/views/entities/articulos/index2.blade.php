{{-- @extends('layouts.layout')

@section('title', 'Articulos')

@section('content')

    <div class="container mt-5 fade-in">
        <div class="mb-4">
            <h1 class="text-white">Artículos index</h1>
        </div>

        @include('components.buscador_filtrado')

        @if (!$hasResults)
            @component('components.no_resultados', ['entityName' => 'artículos'])
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

@endsection --}}


{{-- OPCION CON CARDS 2 --}}

{{-- @extends('layouts.layout')

@section('title', 'Artículos')

@section('content')
    <div class="container mt-5 fade-in">
        <div class="mb-4">
            <h1 class="text-white">Artículos</h1>
        </div>

        @include('components.buscador_filtrado')

        @if (!$hasResults)
            @component('components.no_resultados', ['entityName' => 'artículos'])
            @endcomponent
        @else
            <section class="row index">
                @foreach ($articulos as $articulo)
                    <div class="col-md-4 mb-4">
                        <a class="bs-card" href="{{ route('articulo.show', $articulo->ID_ARTICULO) }}"
                            style="--bs-bg-img: url('{{ asset($articulo->URL_IMAGEN_ARTICULO) }}')">
                            <div>
                                <div class="mb-2">
                                    <h1>{{ $articulo->TITULO_ARTICULO }}</h1>
                                </div>
                                <div class="bs-date">{{ $articulo->FECHA_ARTICULO }}</div>
                                <div class="bs-tags">
                                    <div class="bs-tag">{{ $articulo->REVISTA_ARTICULO }}</div>
                                    <div class="bs-tag">ISSN: {{ $articulo->ISSN_ARTICULO }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </section>
        @endif
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center">
        {{ $articulos->links() }}
    </div>
@endsection --}}


@extends('layouts.public')

@section('title', 'Artículos')

@section('content')

    <div class="container mt-5 fade-in">
        <div class="mb-4">
            <h1 style="color: var(--color-7)">Artículos</h1>
        </div>

        {{-- Formulario de búsqueda y filtrado --}}
        <div class="w-100">
            {{-- @include('components.buscador_filtrado') --}}
            @include('components.buscador')
        </div>


        <div id="resultados">
            @if (!$hasResults)
                @component('components.no_resultados', ['entityName' => 'artículos'])
                @endcomponent
            @else
                <div class="container mt-5">
                    <div class="row justify-content">
                        @foreach ($articulos as $articulo)
                            <div class="col-md-4 mb-4">
                                <a href="{{ route('articulo.show', $articulo->ID_ARTICULO) }}" class="card-link">
                                    <div class="product-card">
                                        <div class="product-card-img-wrapper">
                                            <img src="{{ asset($articulo->URL_IMAGEN_ARTICULO) }}"
                                                class="product-card-img-top" alt="Imagen del artículo">
                                        </div>
                                        <div class="product-card-body">
                                            {{-- <small class="card-meta mb-2">{{ $articulo->REVISTA_ARTICULO }}</small> --}}
                                            <h5 class="product-card-title">
                                                {{ $articulo->TITULO_ARTICULO }}
                                            </h5>
                                            {{-- <p class="card-date"><i class="far fa-clock"></i> {{ $articulo->FECHA_ARTICULO }}
                                        </p> --}}
                                            <p class="product-card-date">
                                                {{-- <i class="far fa-clock"></i>  --}}
                                                {{-- {{ \Carbon\Carbon::parse($articulo->FECHA_ARTICULO)->translatedFormat('d/F/Y') }} --}}
                                                {{ \Carbon\Carbon::parse($articulo->FECHA_ARTICULO)->translatedFormat('l, d \d\e F \d\e Y') }}
                                            </p>
                                            {{-- Línea divisoria --}}
                                            <hr>
                                            <p class="product-card-text">
                                                ISSN: {{ $articulo->ISSN_ARTICULO }}
                                                <br>
                                                Autores: <br>
                                                @foreach ($articulo->autores as $autor)
                                                    {{ $autor->NOMBRE_AUTOR }} {{ $autor->APELLIDO_AUTOR }}@if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach

                                            </p>
                                        </div>

                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center">
        {{ $articulos->links() }}
    </div>
@endsection
