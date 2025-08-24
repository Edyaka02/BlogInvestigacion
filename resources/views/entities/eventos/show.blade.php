@extends('layouts.public')

@section('title', 'Eventos')

@section('content')
    <div class="container mt-5 fade-in">
        <section class="row">
            <article class="col-lg-8 col-md-12 mb-4">
                <div class="mb-4">
                    <h2 class="" style="text-align: center;">{{ $evento->TITULO_EVENTO }}</h2>
                </div>
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <p><strong>Autores:</strong>
                            @foreach ($evento->autores as $autor)
                                {{ $autor->NOMBRE_AUTOR }} {{ $autor->APELLIDO_AUTOR }}@if (!$loop->last),
                                @endif
                            @endforeach
                        </p>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p style="text-align: justify;">{!! nl2br(e($evento->RESUMEN_EVENTO)) !!}</p>
                    </div>
                </div>
            </article>
            <aside class="col-lg-4 col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title mb-3" style="text-align: center;">Información</h3>
                        <p><strong><i class="bi bi-calendar"></i> Nombre:</strong> {{ $evento->NOMBRE_EVENTO }}</p>
                        <p><strong><i class="bi bi-tag"></i> Tipo:</strong> {{ $evento->TIPO_EVENTO }}</p>
                        <p><strong>Modalidad: </strong>{{ $evento->MODALIDAD_EVENTO }}</p>
                        <p><strong>Comunicación: </strong>{{ $evento->COMUNICACION_EVENTO }}</p>
                        <p><strong>Ámbito: </strong>{{ $evento->AMBITO_EVENTO }}</p>
                        <p><strong>Eje Temático: </strong>{{ $evento->EJE_TEMATICO_EVENTO }}</p>
                        @if (!empty($evento->URL_EVENTO))
                            <div style="text-align: center;">
                                <a href="{{ $evento->URL_EVENTO }}" class="btn custom-btn custom-btn-descargar"
                                    download="{{ basename($evento->URL_EVENTO) }}">
                                    <i class="bi bi-download"></i> Descargar PDF</a>
                            </div>
                        @endif
                    </div>
                </div>
            </aside>
        </section>
    </div>
@endsection