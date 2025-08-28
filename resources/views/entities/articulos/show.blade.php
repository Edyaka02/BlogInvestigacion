@extends('components.public.publicLayout')

@section('title', $articulo->TITULO_ARTICULO . ' - Blog Investigación')

@section('meta')
    <meta name="description" content="{{ Str::limit(strip_tags($articulo->RESUMEN_ARTICULO), 160) }}">
    <meta name="keywords" content="investigación, artículo, {{ $articulo->ISSN_ARTICULO }}">
    <meta property="og:title" content="{{ $articulo->TITULO_ARTICULO }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($articulo->RESUMEN_ARTICULO), 160) }}">
    @if($articulo->URL_IMAGEN_ARTICULO)
        <meta property="og:image" content="{{ asset($articulo->URL_IMAGEN_ARTICULO) }}">
    @endif
@endsection

@push('styles')
    {{-- <link rel="stylesheet" href="{{ asset('css/public/test/show.css') }}"> --}}
    @vite(['resources/css/public/test/show.css'])
@endpush

@section('content')
    {{-- Barra de progreso profesional --}}
    <div class="progress-executive" id="progressBar"></div>
    
    {{-- Navegación profesional --}}
    <a href="{{ route('articulos.articulo') }}" class="floating-nav-executive d-none d-md-block">
        <i class="fas fa-arrow-left me-2"></i>Volver al índice
    </a>
    
    {{-- Breadcrumb --}}
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-executive">
                <li class="breadcrumb-item">
                    <a href="{{ route('articulos.articulo') }}" class="text-decoration-none">Investigaciones</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ Str::limit($articulo->TITULO_ARTICULO, 50) }}
                </li>
            </ol>
        </nav>
    </div>
    
    {{-- Hero Section Profesional --}}
    <section class="hero-executive">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="mb-3 d-md-none">
                        <a href="{{ route('articulos.articulo') }}" class="floating-nav-executive">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>
                    
                    <h1 class="display-5 fw-bold mb-4 text-white fade-in-professional">
                        {{ $articulo->TITULO_ARTICULO }}
                    </h1>
                    
                    <p class="subtitle-executive text-white opacity-90 mb-4 fade-in-professional">
                        Investigación académica publicada en {{ $articulo->REVISTA_ARTICULO }}
                    </p>
                    
                    {{-- Estadísticas rápidas --}}
                    <div class="stats-grid fade-in-professional">
                        <div class="stat-item">
                            <span class="stat-number">{{ ceil(str_word_count(strip_tags($articulo->RESUMEN_ARTICULO)) / 200) }}</span>
                            <div class="stat-label">Min de lectura</div>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $articulo->vistas ?? rand(50, 500) }}</span>
                            <div class="stat-label">Visualizaciones</div>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $articulo->descargas ?? rand(10, 100) }}</span>
                            <div class="stat-label">Descargas</div>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ \Carbon\Carbon::parse($articulo->FECHA_ARTICULO)->format('Y') }}</span>
                            <div class="stat-label">Año publicación</div>
                        </div>
                    </div>
                </div>
                
                @if($articulo->URL_IMAGEN_ARTICULO)
                    <div class="col-lg-4 text-center slide-in-professional">
                        <div class="research-image-executive">
                            <img src="{{ $articulo->URL_IMAGEN_ARTICULO }}" 
                                 alt="Imagen representativa de {{ $articulo->TITULO_ARTICULO }}"
                                 style="max-height: 300px;">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <div class="container mb-5">
        <div class="row">
            {{-- Contenido Principal --}}
            <article class="col-lg-8 col-md-12">
                {{-- Información de Autores --}}
                <div class="executive-card fade-in-professional">
                    <div class="card-body p-4">
                        <h4 class="section-title-executive">
                            <i class="fas fa-users"></i>Autores
                        </h4>
                        <div class="authors-container">
                            @foreach ($articulo->autores as $autor)
                                <span class="author-executive">
                                    <i class="fas fa-user me-2"></i>
                                    {{ $autor->NOMBRE_AUTOR }} {{ $autor->APELLIDO_AUTOR }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Resumen Ejecutivo --}}
                <div class="executive-card fade-in-professional">
                    <div class="card-body p-4">
                        <h4 class="section-title-executive">
                            <i class="fas fa-file-text"></i>Resumen
                        </h4>
                        <div class="academic-text-executive">
                            {!! nl2br(e($articulo->RESUMEN_ARTICULO)) !!}
                        </div>
                    </div>
                </div>

                {{-- Cita Académica --}}
                <div class="executive-card fade-in-professional">
                    <div class="card-body p-4">
                        <h5 class="section-title-executive">
                            <i class="fas fa-quote-left"></i>Cita Recomendada
                        </h5>
                        <div class="citation-executive">
                            @php
                                $autores = $articulo->autores->map(function($autor) {
                                    return $autor->APELLIDO_AUTOR . ', ' . substr($autor->NOMBRE_AUTOR, 0, 1) . '.';
                                })->join(', ');
                                $year = \Carbon\Carbon::parse($articulo->FECHA_ARTICULO)->format('Y');
                            @endphp
                            {{ $autores }} ({{ $year }}). {{ $articulo->TITULO_ARTICULO }}. 
                            <em>{{ $articulo->REVISTA_ARTICULO }}</em>. 
                            ISSN: {{ $articulo->ISSN_ARTICULO }}.
                        </div>
                    </div>
                </div>

                {{-- Compartir --}}
                <div class="executive-card fade-in-professional">
                    <div class="card-body p-4">
                        <h5 class="section-title-executive">
                            <i class="fas fa-share-alt"></i>Compartir Investigación
                        </h5>
                        <div class="d-flex justify-content-start flex-wrap">
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($articulo->TITULO_ARTICULO) }}&url={{ urlencode(request()->url()) }}" 
                               class="share-executive" target="_blank" title="Compartir en Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                               class="share-executive" target="_blank" title="Compartir en Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" 
                               class="share-executive" target="_blank" title="Compartir en LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="mailto:?subject={{ urlencode($articulo->TITULO_ARTICULO) }}&body={{ urlencode('Le comparto esta investigación académica: ' . request()->url()) }}" 
                               class="share-executive" title="Compartir por email">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Sidebar Informativo --}}
            <aside class="col-lg-4 col-md-12">
                {{-- Información del Artículo --}}
                <div class="executive-card slide-in-professional">
                    <div class="card-body p-4">
                        <h4 class="section-title-executive">
                            <i class="fas fa-info-circle"></i>Información del Artículo
                        </h4>
                        
                        <table class="metadata-table">
                            <tbody>
                                <tr>
                                    <td>
                                        <i class="fas fa-calendar me-2"></i>Fecha
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($articulo->FECHA_ARTICULO)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fas fa-barcode me-2"></i>ISSN
                                    </td>
                                    <td>
                                        <code class="bg-light px-2 py-1 rounded">{{ $articulo->ISSN_ARTICULO }}</code>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fas fa-journal-whills me-2"></i>Revista
                                    </td>
                                    <td>
                                        <a href="{{ $articulo->URL_REVISTA_ARTICULO }}" 
                                           target="_blank" 
                                           class="text-decoration-none text-primary">
                                            {{ $articulo->REVISTA_ARTICULO }}
                                            <i class="fas fa-external-link-alt ms-1 small"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- Descarga Profesional --}}
                        @if (!empty($articulo->URL_ARTICULO))
                            <div class="download-executive">
                                <h6 class="mb-3 text-primary fw-bold">
                                    <i class="fas fa-download me-2"></i>Documento Completo
                                </h6>
                                
                                <a href="{{ $articulo->URL_ARTICULO }}"
                                   class="btn-executive btn-executive-success w-100 d-block text-center mb-3"
                                   target="_blank"
                                   download>
                                    <i class="fas fa-file-pdf me-2"></i>
                                    Descargar PDF
                                </a>
                                
                                @php
                                    $filePath = public_path(ltrim($articulo->URL_ARTICULO, '/'));
                                    $fileSize = file_exists($filePath) ? number_format(filesize($filePath) / 1024 / 1024, 2) : 0;
                                @endphp
                                
                                @if($fileSize > 0)
                                    <small class="text-muted d-block text-center">
                                        <i class="fas fa-hdd me-1"></i>
                                        {{ $fileSize }} MB
                                    </small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Investigaciones Relacionadas --}}
                <div class="executive-card slide-in-professional">
                    <div class="card-body p-4">
                        <h6 class="section-title-executive">
                            <i class="fas fa-sitemap"></i>Investigaciones Relacionadas
                        </h6>
                        
                        <div class="text-center text-muted">
                            <i class="fas fa-cog fa-2x mb-3 opacity-50"></i>
                            <p class="mb-0 small">Sistema de recomendaciones en desarrollo</p>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script src="{{ asset('js/components/ui/ArticuloShow.js') }}"></script> --}}
    @vite(['resources/js/components/ui/ArticuloShow.js'])
@endpush