{{-- resources/views/entities/proyectos/show.blade.php --}}
@extends('components.public.publicLayout')

@section('title', $proyecto->TITULO_PROYECTO . ' - Blog Investigación')

@section('meta')
    {{-- SEO Básico --}}
    <meta name="description"
        content="{{ Str::limit($proyecto->RESUMEN_PROYECTO ?: 'Proyecto de investigación: ' . $proyecto->TITULO_PROYECTO, 160) }}">
    <meta name="keywords"
        content="investigación, proyecto, {{ $proyecto->CONVOCATORIA_PROYECTO }}, {{ $proyecto->organismo->NOMBRE_ORGANISMO ?? '' }}, {{ $proyecto->ambito->NOMBRE_AMBITO ?? '' }}, {{ implode(', ', $proyecto->autores->pluck('APELLIDO_AUTOR')->toArray()) }}">
    <meta name="author"
        content="{{ $proyecto->autores->pluck('NOMBRE_AUTOR', 'APELLIDO_AUTOR')->map(fn($nombre, $apellido) => $nombre . ' ' . $apellido)->join(', ') }}">

    {{-- Open Graph (Facebook, LinkedIn) --}}
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $proyecto->TITULO_PROYECTO }}">
    <meta property="og:description"
        content="{{ Str::limit($proyecto->RESUMEN_PROYECTO ?: 'Proyecto de investigación: ' . $proyecto->TITULO_PROYECTO, 160) }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:site_name" content="Blog Investigación">
    @if ($proyecto->URL_IMAGEN_PROYECTO)
        <meta property="og:image" content="{{ asset('storage/' . ltrim($proyecto->URL_IMAGEN_PROYECTO, '/')) }}">
        <meta property="og:image:alt" content="Imagen del proyecto {{ $proyecto->TITULO_PROYECTO }}">
    @endif

    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $proyecto->TITULO_PROYECTO }}">
    <meta name="twitter:description"
        content="{{ Str::limit($proyecto->RESUMEN_PROYECTO ?: 'Proyecto de investigación: ' . $proyecto->TITULO_PROYECTO, 160) }}">
    @if ($proyecto->URL_IMAGEN_PROYECTO)
        <meta name="twitter:image" content="{{ asset('storage/' . ltrim($proyecto->URL_IMAGEN_PROYECTO, '/')) }}">
    @endif

    {{-- Académico/Investigación --}}
    <meta name="citation_title" content="{{ $proyecto->TITULO_PROYECTO }}">
    <meta name="citation_publication_date"
        content="{{ \Carbon\Carbon::parse($proyecto->FECHA_PROYECTO)->format('Y/m/d') }}">
    <meta name="citation_publisher" content="{{ $proyecto->organismo->NOMBRE_ORGANISMO ?? 'Organismo desconocido' }}">
    <meta name="citation_conference_title" content="{{ $proyecto->CONVOCATORIA_PROYECTO }}">
    @foreach ($proyecto->autores as $autor)
        <meta name="citation_author" content="{{ $autor->APELLIDO_AUTOR }}, {{ $autor->NOMBRE_AUTOR }}">
    @endforeach
    @if ($proyecto->URL_PROYECTO)
        <meta name="citation_pdf_url" content="{{ asset('storage/' . ltrim($proyecto->URL_PROYECTO, '/')) }}">
    @endif

@endsection

@section('content')
    {{-- Barra de progreso profesional --}}
    <div class="progress-executive" id="progressBar"></div>

    {{-- Navegación profesional --}}
    <a href="{{ route('proyectos.index') }}" class="floating-nav-executive d-none d-md-block">
        <i class="fas fa-arrow-left me-2"></i>Volver al índice
    </a>

    {{-- Hero Section Profesional --}}
    <section class="hero-executive">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="mb-3 d-md-none">
                        <a href="{{ route('proyectos.index') }}" class="floating-nav-executive">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>

                    <h1 class="display-5 fw-bold mb-4 text-white fade-in-professional">
                        {{ $proyecto->TITULO_PROYECTO }}
                    </h1>

                    <p class="subtitle-executive text-white opacity-90 mb-4 fade-in-professional">
                        Proyecto de investigación financiado por
                        {{ $proyecto->organismo->NOMBRE_ORGANISMO ?? 'Organismo desconocido' }}
                        @if ($proyecto->ambito)
                            en el ámbito de {{ $proyecto->ambito->NOMBRE_AMBITO }}
                        @endif
                    </p>

                    {{-- Estadísticas rápidas --}}
                    <div class="stats-grid fade-in-professional">
                        <div class="stat-item">
                            <span
                                class="stat-number">{{ ceil(str_word_count(strip_tags($proyecto->RESUMEN_PROYECTO)) / 200) }}</span>
                            <div class="stat-label">Min de lectura</div>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ number_format($proyecto->VISTA_PROYECTO ?? 0) }}</span>
                            <div class="stat-label">Visualizaciones</div>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ number_format($proyecto->DESCARGA_PROYECTO ?? 0) }}</span>
                            <div class="stat-label">Descargas</div>
                        </div>
                        <div class="stat-item">
                            <span
                                class="stat-number">{{ \Carbon\Carbon::parse($proyecto->FECHA_PROYECTO)->format('Y') }}</span>
                            <div class="stat-label">Año inicio</div>
                        </div>
                    </div>
                </div>

                @if ($proyecto->URL_IMAGEN_PROYECTO)
                    <div class="col-lg-4 text-center slide-in-professional">
                        <div class="research-image-executive">
                            <img src="{{ asset($proyecto->URL_IMAGEN_PROYECTO) }}"
                                alt="Imagen del proyecto {{ $proyecto->TITULO_PROYECTO }}"
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
                {{-- Información de Investigadores --}}
                <div class="executive-card fade-in-professional">
                    <div class="card-body p-4">
                        <h4 class="section-title-executive">
                            <i class="fas fa-users"></i>Equipo de Investigación
                        </h4>
                        <div class="authors-container">
                            @foreach ($proyecto->autores as $autor)
                                <span class="author-executive">
                                    <i class="fas fa-user me-2"></i>
                                    {{ $autor->NOMBRE_AUTOR }} {{ $autor->APELLIDO_AUTOR }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Información del Proyecto --}}
                @if ($proyecto->RESUMEN_PROYECTO)
                    <div class="executive-card fade-in-professional">
                        <div class="card-body p-4">
                            <h4 class="section-title-executive">
                                <i class="fas fa-file-alt"></i>Resumen del Proyecto
                            </h4>
                            <div class="academic-text-executive">
                                {{ $proyecto->RESUMEN_PROYECTO }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="executive-card fade-in-professional">
                        <div class="card-body p-4">
                            <h4 class="section-title-executive">
                                <i class="fas fa-project-diagram"></i>Proyecto de Investigación
                            </h4>
                            <div class="academic-text-executive">
                                <strong>{{ $proyecto->TITULO_PROYECTO }}</strong> es un proyecto de investigación
                                desarrollado
                                en el marco de la convocatoria <em>{{ $proyecto->CONVOCATORIA_PROYECTO }}</em>.
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Información de Financiamiento --}}
                <div class="executive-card fade-in-professional">
                    <div class="card-body p-4">
                        <h5 class="section-title-executive">
                            <i class="fas fa-coins"></i>Información de Financiamiento
                        </h5>
                        <div class="funding-container">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="funding-item">
                                        <h6 class="fw-bold text-primary">
                                            <i class="fas fa-building me-2"></i>Organismo Financiador
                                        </h6>
                                        <p class="mb-2">
                                            {{ $proyecto->organismo->NOMBRE_ORGANISMO ?? 'No especificado' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="funding-item">
                                        <h6 class="fw-bold text-success">
                                            <i class="fas fa-bullhorn me-2"></i>Convocatoria
                                        </h6>
                                        <p class="mb-2">
                                            <span class="badge bg-primary">{{ $proyecto->CONVOCATORIA_PROYECTO }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @if ($proyecto->ambito)
                                <div class="funding-item mt-3">
                                    <h6 class="fw-bold text-info">
                                        <i class="fas fa-tag me-2"></i>Ámbito de Investigación
                                    </h6>
                                    <p class="mb-0">{{ $proyecto->ambito->NOMBRE_AMBITO }}</p>
                                </div>
                            @endif
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
                                $investigadores = $proyecto->autores
                                    ->map(function ($autor) {
                                        return $autor->APELLIDO_AUTOR . ', ' . substr($autor->NOMBRE_AUTOR, 0, 1) . '.';
                                    })
                                    ->join(', ');
                                $year = \Carbon\Carbon::parse($proyecto->FECHA_PROYECTO)->format('Y');
                            @endphp
                            {{ $investigadores }} ({{ $year }}). {{ $proyecto->TITULO_PROYECTO }}.
                            <em>{{ $proyecto->CONVOCATORIA_PROYECTO }}</em>.
                            {{ $proyecto->organismo->NOMBRE_ORGANISMO ?? 'Organismo financiador' }}.
                            @if ($proyecto->ambito)
                                Ámbito: {{ $proyecto->ambito->NOMBRE_AMBITO }}.
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Compartir --}}
                <div class="executive-card fade-in-professional">
                    <div class="card-body p-4">
                        <h5 class="section-title-executive">
                            <i class="fas fa-share-alt"></i>Compartir Proyecto
                        </h5>
                        <div class="d-flex justify-content-start flex-wrap">
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($proyecto->TITULO_PROYECTO) }}&url={{ urlencode(request()->url()) }}"
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
                            <a href="mailto:?subject={{ urlencode($proyecto->TITULO_PROYECTO) }}&body={{ urlencode('Le comparto este proyecto de investigación: ' . request()->url()) }}"
                                class="share-executive" title="Compartir por email">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Sidebar Informativo --}}
            <aside class="col-lg-4 col-md-12">
                {{-- Información del Proyecto --}}
                <div class="executive-card slide-in-professional">
                    <div class="card-body p-4">
                        <h4 class="section-title-executive">
                            <i class="fas fa-info-circle"></i>Información del Proyecto
                        </h4>

                        <table class="metadata-table">
                            <tbody>
                                <tr>
                                    <td>
                                        <i class="fas fa-calendar me-2"></i>Fecha Inicio
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($proyecto->FECHA_PROYECTO)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fas fa-building me-2"></i>Organismo
                                    </td>
                                    <td class="fw-medium ">
                                        {{ $proyecto->organismo->NOMBRE_ORGANISMO ?? 'No especificado' }}</td>
                                </tr>
                                @if ($proyecto->ambito)
                                    <tr>
                                        <td>
                                            <i class="fas fa-tag me-2"></i>Ámbito
                                        </td>
                                        <td class="fst-italic">{{ $proyecto->ambito->NOMBRE_AMBITO }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        {{-- Descarga Profesional --}}
                        @if (!empty($proyecto->URL_PROYECTO))
                            <div class="download-executive">
                                <h6 class="mb-3 text-primary fw-bold">
                                    <i class="fas fa-download me-2"></i>Documento del Proyecto
                                </h6>

                                @php
                                    $filePath = public_path(ltrim($proyecto->URL_PROYECTO, '/'));
                                @endphp

                                @if (file_exists($filePath))
                                    <a href="{{ route('entities.proyectos.download', $proyecto->ID_PROYECTO) }}"
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

                {{-- Proyectos Relacionados --}}
                <div class="executive-card slide-in-professional">
                    <div class="card-body p-4">
                        <h6 class="section-title-executive">
                            <i class="fas fa-sitemap"></i>Proyectos Relacionados
                        </h6>

                        @if ($proyecto->organismo || $proyecto->ambito)
                            <div class="text-center text-muted mb-3">
                                <small>Otros proyectos del mismo:</small>
                            </div>

                            @if ($proyecto->organismo)
                                <div class="mb-2">
                                    <a href="{{ route('proyectos.index') }}?organismo[]={{ $proyecto->ID_ORGANISMO }}"
                                        class="btn btn-outline-primary btn-sm w-100">
                                        <i class="fas fa-building me-1"></i>
                                        {{ $proyecto->organismo->NOMBRE_ORGANISMO }}
                                    </a>
                                </div>
                            @endif

                            @if ($proyecto->ambito)
                                <div class="mb-2">
                                    <a href="{{ route('proyectos.index') }}?ambito[]={{ $proyecto->ID_AMBITO }}"
                                        class="btn btn-outline-info btn-sm w-100">
                                        <i class="fas fa-tag me-1"></i>
                                        {{ $proyecto->ambito->NOMBRE_AMBITO }}
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-cog fa-2x mb-3 opacity-50"></i>
                                <p class="mb-0 small">Sistema de recomendaciones en desarrollo</p>
                            </div>
                        @endif
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/components/ui/ShowPageManager.js'])
@endpush
