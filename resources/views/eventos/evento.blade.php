@extends('layouts.layout')

@section('title', 'Eventos')

@section('content')

    <div class="container mt-5 fade-in">
        <div class="mb-4">
            <h1>Eventos</h1>
        </div>

        {{-- Formulario de búsqueda y filtrado --}}
        @include('admin.components.buscador_filtrado')

        @if (!$hasResults)
            @component('admin.components.no_resultados', ['entityName' => 'eventos'])
            @endcomponent
        @else
            <div class="row index">
                @foreach ($eventos as $evento)
                    <div class="col-md-4 mb-4">
                        <a href="{{ route('evento.show', $evento->ID_EVENTO) }}" class="card-link">
                            <div class="card text-dark card-has-bg click-col"
                                style="background-image:url('{{ asset($evento->URL_IMAGEN_EVENTO) }}');">
                                <img class="card-img d-none" src="{{ asset($evento->URL_IMAGEN_EVENTO) }}"
                                    alt="Imagen del evento">
                                <div class="card-img-overlay d-flex flex-column">
                                    <div class="card-body">
                                        <small class="card-meta mb-2">{{ $evento->TIPO_EVENTO }}</small>
                                        <h4 class="card-title text-white mt-0">
                                            {{ $evento->TITULO_EVENTO }}
                                        </h4>
                                        <div>
                                            <small class="card-meta-category mb-2"><i class="far fa-calendar"></i>
                                                {{ $evento->NOMBRE_EVENTO }}</small>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="media">
                                            <div class="media-body">
                                                <h6 class="my-0 text-white d-block">Autor</h6>
                                                <small class="card-meta-category">
                                                    @foreach ($evento->autores as $autor)
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
        {{ $eventos->links() }}
    </div>

@endsection