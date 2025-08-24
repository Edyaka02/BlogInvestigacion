@extends('layouts.public')

@section('title', 'Articulos')


@section('content')
    <div class="container mt-5 fade-in">
        <section class="row">
            <article class="col-lg-8 col-md-12 mb-4">
                <div class="mb-4">
                    <h2 class="" style="text-align: center;">{{ $articulo->TITULO_ARTICULO }} show</h2>
                </div>
                <div class="card shadow-sm show-card mb-4">
                    <div class="card-body">
                        <p><strong>Autores:</strong>
                            @foreach ($articulo->autores as $autor)
                                {{ $autor->NOMBRE_AUTOR }} {{ $autor->APELLIDO_AUTOR }}@if (!$loop->last),
                                @endif
                            @endforeach
                        </p>
                    </div>
                </div>
                <div class="card shadow-sm show-card">
                    <div class="card-body">
                        <p style="text-align: justify;">{!! nl2br(e($articulo->RESUMEN_ARTICULO)) !!}</p>
                    </div>
                </div>
            </article>
            <aside class="col-lg-4 col-md-12">
                <div class="card shadow-sm show-card">
                    <div class="card-body">
                        <h3 class="card-title mb-3" style="text-align: center;">Información</h3>
                        <p><strong><i class="bi bi-calendar"></i> Fecha:</strong> {{ $articulo->FECHA_ARTICULO }}</p>
                        <p><strong><i class="bi bi-tag"></i> ISSN:</strong> {{ $articulo->ISSN_ARTICULO }}</p>
                        <p><strong>Revista: </strong><a
                                href="{{ $articulo->URL_REVISTA_ARTICULO }}">{{ $articulo->REVISTA_ARTICULO }}</a></p>
                        @if (!empty($articulo->URL_ARTICULO))
                            <div style="text-align: center;">
                                <a href="{{ $articulo->URL_ARTICULO }}" class="btn custom-btn custom-btn-descargar"
                                    download="{{ basename($articulo->URL_ARTICULO) }}">
                                    <i class="bi bi-download"></i> Descargar PDF</a>
                            </div>
                        @endif
                    </div>
                </div>
            </aside>
        </section>
    </div>
@endsection
