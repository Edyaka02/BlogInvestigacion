@extends('layouts.layout')

@section('title', 'Articulos')

@section('content')

    {{-- <h1>Artículos</h1>
    <div class="row">
        @foreach ($articulos as $articulo)
            <div class="col-md-3 mb-4">
                <div class="card card-1">
                    <img loading="lazy" class="card__background img-fluid" src="{{ asset($articulo->URL_IMAGEN_ARTICULO) }}"
                        alt="Imagen del artículo" />
                    <div class="card__content flow">
                        <div class="card__content--container flow">
                            <h5 class="card__title mb-3">{{ $articulo->TITULO_ARTICULO }}</h5>

                            <div class="mb-3">
                                <p class="card__description">
                                    Autores:
                                    @foreach ($articulo->autores as $autor)
                                        {{ $autor->NOMBRE_AUTOR }} {{ $autor->APELLIDO_AUTOR }}@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </p>
                                <p class="card__description">
                                    Fecha: {{ $articulo->FECHA_ARTICULO }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('articulo.show', $articulo->ID_ARTICULO) }}" class="card__button">Read more</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div> --}}

    {{-- <h1>Artículos</h1>
    <div class="row">
        @foreach ($articulos as $articulo)
            <div class="col-md-3 mb-4">
                <div class="card card-1">
                    <div class="card__image-container">
                        <img loading="lazy" class="img-fluid"
                            src="{{ asset($articulo->URL_IMAGEN_ARTICULO) }}" alt="Imagen del artículo" />
                    </div>
                    <div class="card__content flow">
                        <div class="card__content--container flow">
                            <h5 class="card__title mb-3">{{ $articulo->TITULO_ARTICULO }}</h5>

                            <div class="mb-3">
                                <p class="card__description">
                                    Autores:
                                    @foreach ($articulo->autores as $autor)
                                        {{ $autor->NOMBRE_AUTOR }} {{ $autor->APELLIDO_AUTOR }}@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </p>
                                <p class="card__description">
                                    Fecha: {{ $articulo->FECHA_ARTICULO }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('articulo.show', $articulo->ID_ARTICULO) }}" class="card__button">Read more</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div> --}}

    <h1>Artículos</h1>
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
                                <h4 class="card-title text-white mt-0">
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
                                    {{-- <img class="mr-3 rounded-circle" src="https://assets.codepen.io/460692/internal/avatars/users/default.png?format=auto&version=1688931977&width=80&height=80" alt="Generic placeholder image" style="max-width:50px"> --}}
                                    <div class="media-body">
                                        <h6 class="my-0 text-white d-block">Autor</h6>
                                        <small class="card-meta-category">
                                            @foreach ($articulo->autores as $autor)
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

    <!-- Paginación -->
    <div class="d-flex justify-content-center">
        {{ $articulos->links() }}
    </div>

@endsection
