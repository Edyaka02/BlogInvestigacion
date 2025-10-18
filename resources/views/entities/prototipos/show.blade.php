{{-- filepath: c:\laragon\www\BlogInvestigacion\resources\views\entities\prototipos\show.blade.php --}}
{{-- resources/views/entities/prototipos/show.blade.php --}}
@extends('components.public.publicLayout')

@section('title', $prototipo->NOMBRE_PROTOTIPO . ' - Blog Investigación')

@section('meta')
    {{-- SEO Básico --}}
    <meta name="description"
        content="{{ Str::limit($prototipo->DESCRIPCION_PROTOTIPO ?: 'Prototipo desarrollado: ' . $prototipo->NOMBRE_PROTOTIPO, 160) }}">
    <meta name="keywords"
        content="investigación, prototipo, {{ $prototipo->PROPOSITO_PROTOTIPO }}, {{ $prototipo->INSTITUCION_PROTOTIPO }}, {{ implode(', ', $prototipo->autores->pluck('APELLIDO_AUTOR')->toArray()) }}">
    <meta name="author"
        content="{{ $prototipo->autores->pluck('NOMBRE_AUTOR', 'APELLIDO_AUTOR')->map(fn($nombre, $apellido) => $nombre . ' ' . $apellido)->join(', ') }}">

    {{-- Open Graph (Facebook, LinkedIn) --}}
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $prototipo->NOMBRE_PROTOTIPO }}">
    <meta property="og:description"
        content="{{ Str::limit($prototipo->DESCRIPCION_PROTOTIPO ?: 'Prototipo desarrollado: ' . $prototipo->NOMBRE_PROTOTIPO, 160) }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:site_name" content="Blog Investigación">
    @if ($prototipo->URL_IMAGEN_PROTOTIPO)
        <meta property="og:image" content="{{ asset('storage/' . ltrim($prototipo->URL_IMAGEN_PROTOTIPO, '/')) }}">
        <meta property="og:image:alt" content="Imagen del prototipo {{ $prototipo->NOMBRE_PROTOTIPO }}">
    @endif

    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $prototipo->NOMBRE_PROTOTIPO }}">
    <meta name="twitter:description"
        content="{{ Str::limit($prototipo->DESCRIPCION_PROTOTIPO ?: 'Prototipo desarrollado: ' . $prototipo->NOMBRE_PROTOTIPO, 160) }}">
    @if ($prototipo->URL_IMAGEN_PROTOTIPO)
        <meta name="twitter:image" content="{{ asset('storage/' . ltrim($prototipo->URL_IMAGEN_PROTOTIPO, '/')) }}">
    @endif

    {{-- Académico/Investigación --}}
    <meta name="citation_title" content="{{ $prototipo->NOMBRE_PROTOTIPO }}">
    <meta name="citation_publication_date"
        content="{{ \Carbon\Carbon::parse($prototipo->FECHA_PROTOTIPO)->format('Y/m/d') }}">
    <meta name="citation_publisher" content="{{ $prototipo->INSTITUCION_PROTOTIPO }}">
    @foreach ($prototipo->autores as $autor)
        <meta name="citation_author" content="{{ $autor->APELLIDO_AUTOR }}, {{ $autor->NOMBRE_AUTOR }}">
    @endforeach
    @if ($prototipo->URL_PROTOTIPO)
        <meta name="citation_pdf_url" content="{{ asset('storage/' . ltrim($prototipo->URL_PROTOTIPO, '/')) }}">
    @endif

@endsection

@section('content')
    {{-- Barra de progreso profesional --}}
    <div class="progress-executive" id="progressBar"></div>

    {{-- Navegación profesional --}}
    <a href="{{ route('prototipos.index') }}" class="floating-nav-executive d-none d-md-block">
        <i class="fas fa-arrow-left me-2"></i>Volver al índice
    </a>

    {{-- Hero Section Profesional --}}
    <section class="hero-executive">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="mb-3 d-md-none">
                        <a href="{{ route('prototipos.index') }}" class="floating-nav-executive">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>

                    <h1 class="display-5 fw-bold mb-4 text-white fade-in-professional">
                        {{ $prototipo->NOMBRE_PROTOTIPO }}
                    </h1>

                    <p class="subtitle-executive text-white opacity-90 mb-4 fade-in-professional">
                        Prototipo desarrollado en {{ $prototipo->INSTITUCION_PROTOTIPO }}
                        - {{ $prototipo->PROPOSITO_PROTOTIPO }}
                    </p>

                    {{-- Estadísticas rápidas --}}
                    <div class="stats-grid fade-in-professional">
                        <div class="stat-item">
                            {{-- <span class="stat-number">{{ $prototipo->PROPOSITO_PROTOTIPO }}</span>
                            <div class="stat-label">Propósito</div> --}}
                            <span
                                class="stat-number">{{ ceil(str_word_count(strip_tags($prototipo->DESCRIPCION_PROTOTIPO)) / 200) }}</span>
                            <div class="stat-label">Min de lectura</div>
                        </div>

                        <div class="stat-item">
                            <span class="stat-number">{{ number_format($prototipo->VISTA_PROTOTIPO ?? 0) }}</span>
                            <div class="stat-label">Visualizaciones</div>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ number_format($prototipo->DESCARGA_PROTOTIPO ?? 0) }}</span>
                            <div class="stat-label">Descargas</div>
                        </div>
                        <div class="stat-item">
                            <span
                                class="stat-number">{{ \Carbon\Carbon::parse($prototipo->FECHA_PROTOTIPO)->format('Y') }}</span>
                            <div class="stat-label">Año desarrollo</div>
                        </div>
                    </div>
                </div>

                @if ($prototipo->URL_IMAGEN_PROTOTIPO)
                    <div class="col-lg-4 text-center slide-in-professional">
                        <div class="research-image-executive">
                            <img src="{{ asset($prototipo->URL_IMAGEN_PROTOTIPO) }}"
                                alt="Imagen del prototipo {{ $prototipo->NOMBRE_PROTOTIPO }}"
                                style="max-height: 350px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);"
                                onerror="this.src='/assets/img/default-article.png'">
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
                            @foreach ($prototipo->autores as $autor)
                                <span class="author-executive">
                                    <i class="fas fa-user me-2"></i>
                                    {{ $autor->NOMBRE_AUTOR }} {{ $autor->APELLIDO_AUTOR }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Descripción del Prototipo --}}
                <div class="executive-card fade-in-professional">
                    <div class="card-body p-4">
                        <h4 class="section-title-executive">
                            <i class="fas fa-align-left"></i>Descripción
                        </h4>
                        <div class="academic-text-executive">
                            {{ $prototipo->DESCRIPCION_PROTOTIPO }}
                        </div>
                    </div>
                </div>

                {{-- Objetivo --}}
                <div class="executive-card fade-in-professional">
                    <div class="card-body p-4">
                        <h4 class="section-title-executive">
                            <i class="fas fa-target"></i>Objetivo
                        </h4>
                        <div class="academic-text-executive">
                            {{ $prototipo->OBJETIVO_PROTOTIPO }}
                        </div>
                    </div>
                </div>

                {{-- Características --}}
                <div class="executive-card fade-in-professional">
                    <div class="card-body p-4">
                        <h4 class="section-title-executive">
                            <i class="fas fa-list"></i>Características
                        </h4>
                        <div class="academic-text-executive">
                            {{ $prototipo->CARACTERISTICAS_PROTOTIPO }}
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
                                $autores = $prototipo->autores
                                    ->map(function ($autor) {
                                        return $autor->APELLIDO_AUTOR . ', ' . substr($autor->NOMBRE_AUTOR, 0, 1) . '.';
                                    })
                                    ->join(', ');
                                $year = \Carbon\Carbon::parse($prototipo->FECHA_PROTOTIPO)->format('Y');
                            @endphp
                            {{ $autores }} ({{ $year }}). {{ $prototipo->NOMBRE_PROTOTIPO }}.
                            <em>{{ $prototipo->PROPOSITO_PROTOTIPO }}</em>.
                            {{ $prototipo->INSTITUCION_PROTOTIPO }}.
                        </div>
                    </div>
                </div>

                {{-- Compartir --}}
                <div class="executive-card fade-in-professional">
                    <div class="card-body p-4">
                        <h5 class="section-title-executive">
                            <i class="fas fa-share-alt"></i>Compartir Prototipo
                        </h5>
                        <div class="d-flex justify-content-start flex-wrap">
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($prototipo->NOMBRE_PROTOTIPO) }}&url={{ urlencode(request()->url()) }}"
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
                            <a href="mailto:?subject={{ urlencode($prototipo->NOMBRE_PROTOTIPO) }}&body={{ urlencode('Le comparto este prototipo de investigación: ' . request()->url()) }}"
                                class="share-executive" title="Compartir por email">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Sidebar Informativo --}}
            <aside class="col-lg-4 col-md-12">
                {{-- Información del Prototipo --}}
                <div class="executive-card slide-in-professional">
                    <div class="card-body p-4">
                        <h4 class="section-title-executive">
                            <i class="fas fa-info-circle"></i>Información del Prototipo
                        </h4>

                        <table class="metadata-table">
                            <tbody>
                                <tr>
                                    <td>
                                        <i class="fas fa-calendar me-2"></i>Fecha
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($prototipo->FECHA_PROTOTIPO)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fas fa-bullseye me-2"></i>Propósito
                                    </td>
                                    <td class="fw-medium">{{ $prototipo->PROPOSITO_PROTOTIPO }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fas fa-building me-2"></i>Institución
                                    </td>
                                    <td class="fw-medium">{{ $prototipo->INSTITUCION_PROTOTIPO }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fas fa-eye me-2"></i>Vistas
                                    </td>
                                    <td>{{ number_format($prototipo->VISTA_PROTOTIPO ?? 0) }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fas fa-download me-2"></i>Descargas
                                    </td>
                                    <td>{{ number_format($prototipo->DESCARGA_PROTOTIPO ?? 0) }}</td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- Descarga Profesional --}}
                        @if (!empty($prototipo->URL_PROTOTIPO))
                            <div class="download-executive">
                                <h6 class="mb-3 text-primary fw-bold">
                                    <i class="fas fa-download me-2"></i>Documento del Prototipo
                                </h6>

                                @php
                                    $filePath = public_path(ltrim($prototipo->URL_PROTOTIPO, '/'));
                                @endphp

                                @if (file_exists($filePath))
                                    <a href="{{ route('prototipos.download', $prototipo->ID_PROTOTIPO) }}"
                                        class="custom-button custom-button-success w-100 d-block text-center mb-3">
                                        <i class="fas fa-file-pdf me-2"></i>
                                        Descargar PDF
                                    </a>
                                @else
                                    <div class="alert alert-warning text-center">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Documento no disponible
                                    </div>
                                @endif

                                @php
                                    $fileSize = file_exists($filePath)
                                        ? number_format(filesize($filePath) / 1024 / 1024, 2)
                                        : 0;
                                @endphp

                                @if ($fileSize > 0)
                                    <small class="text-muted d-block text-center">
                                        <i class="fas fa-hdd me-1"></i>
                                        {{ $fileSize }} MB
                                    </small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Prototipos Relacionados --}}
                <div class="executive-card slide-in-professional">
                    <div class="card-body p-4">
                        <h6 class="section-title-executive">
                            <i class="fas fa-sitemap"></i>Prototipos Relacionados
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
    @vite(['resources/js/components/ui/ShowPageManager.js'])
@endpush
