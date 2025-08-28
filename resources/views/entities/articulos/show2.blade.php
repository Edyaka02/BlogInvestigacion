@extends('components.public.publicLayout')

@section('title', 'Articulos')

@section('content')
    <div class="container mt-5 fade-in">
        <section class="row">
            <article class="col-lg-8 col-md-12 mb-4">
                <div class="mb-4">
                    <h2 class="text-center">{{ $articulo->TITULO_ARTICULO }}</h2>
                </div>

                {{-- ✅ CAMBIO: Nueva estructura de imágenes --}}
                {{-- @if ($articulo->URL_IMAGEN_ARTICULO)
                    <div class="text-center mb-4">
                        <img src="{{ $articulo->URL_IMAGEN_ARTICULO }}" class="img-fluid rounded shadow"
                            alt="Portada de {{ $articulo->TITULO_ARTICULO }}" style="max-height: 400px; object-fit: cover;">
                    </div>
                @endif --}}

                <div class="card shadow-sm show-card mb-4">
                    <div class="card-body">
                        <p><strong>Autores:</strong>
                            @foreach ($articulo->autores as $autor)
                                {{ $autor->NOMBRE_AUTOR }} {{ $autor->APELLIDO_AUTOR }}@if (!$loop->last)
                                    ,
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
                        <h3 class="card-title mb-3 text-center">Información</h3>
                        <p><strong><i class="bi bi-calendar"></i> Fecha:</strong> {{ $articulo->FECHA_ARTICULO }}</p>
                        <p><strong><i class="bi bi-tag"></i> ISSN:</strong> {{ $articulo->ISSN_ARTICULO }}</p>
                        <p><strong>Revista:</strong>
                            <a href="{{ $articulo->URL_REVISTA_ARTICULO }}" target="_blank">
                                {{ $articulo->REVISTA_ARTICULO }}
                            </a>
                        </p>

                        {{-- Botón de descarga mejorado --}}
                        @if (!empty($articulo->URL_ARTICULO))
                            <div class="text-center mt-4">
                                <a href="{{ $articulo->URL_ARTICULO }}"
                                    class="btn custom-btn custom-btn-azul" target="_blank"
                                    download>
                                    <i class="fa-solid fa-download"></i>
                                    <span class="btn-text-responsive">Descargar</span> PDF
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </aside>
        </section>
    </div>
@endsection
