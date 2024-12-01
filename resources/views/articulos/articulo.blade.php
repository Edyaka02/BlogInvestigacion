@extends('layouts.layout')

@section('title', 'Articulos')

@section('content')
    
    <h1>Artículos</h1>
    <div class="row">
        @foreach ($articulos as $articulo)
            <div class="col-md-3 mb-4">
                <div class="card card-1">
                    <img loading="lazy" class="card__background" src="{{ $articulo->URL_IMAGEN_ARTICULO }}"
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
                        <a href="{{ route('articulo.show', $articulo->ID_ARTICULO) }}"  class="card__button">Read more</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
