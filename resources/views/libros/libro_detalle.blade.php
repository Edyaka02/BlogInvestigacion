@extends('layouts.layout')

@section('title', 'Libros')

@section('content')
    <div class="container mt-5 fade-in">
        <section class="row">
            <article class="col-lg-8 col-md-12 mb-4">
                <div class="mb-4">
                    <h2 class="" style="text-align: center;">{{ $libro->TITULO_LIBRO }}</h2>
                </div>
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <p><strong>Autores:</strong>
                            @foreach ($libro->autores as $autor)
                                {{ $autor->NOMBRE_AUTOR }} {{ $autor->APELLIDO_AUTOR }}@if (!$loop->last),
                                @endif
                            @endforeach
                        </p>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p style="text-align: justify;">{!! nl2br(e($libro->CAPITULO_LIBRO)) !!}</p>
                    </div>
                </div>
            </article>
            <aside class="col-lg-4 col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title mb-3" style="text-align: center;">Información</h3>
                        <p><strong><i class="bi bi-calendar"></i> Año:</strong> {{ $libro->YEAR_LIBRO }}</p>
                        <p><strong><i class="bi bi-tag"></i> ISBN:</strong> {{ $libro->ISBN_LIBRO }}</p>
                        <p><strong>Editorial: </strong>{{ $libro->EDITORIAL_LIBRO }}</p>
                        @if (!empty($libro->URL_LIBRO))
                            <div style="text-align: center;">
                                <a href="{{ $libro->URL_LIBRO }}" class="btn custom-btn custom-btn-descargar"
                                    download="{{ basename($libro->URL_LIBRO) }}">
                                    <i class="bi bi-download"></i> Descargar PDF</a>
                            </div>
                        @endif
                    </div>
                </div>
            </aside>
        </section>
    </div>
@endsection