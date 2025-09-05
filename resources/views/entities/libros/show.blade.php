{{-- resources/views/entities/libros/show.blade.php --}}
@extends('components.public.publicLayout')

@section('title', $libro->TITULO_LIBRO . ' - Blog Investigación')

@section('meta')
    {{-- SEO Básico --}}
    <meta name="description" content="{{ Str::limit($libro->CAPITULO_LIBRO ?: 'Libro académico: ' . $libro->TITULO_LIBRO, 160) }}">
    <meta name="keywords"
        content="investigación, libro, {{ $libro->ISBN_LIBRO }}, {{ $libro->EDITORIAL_LIBRO }}, {{ implode(', ', $libro->autores->pluck('APELLIDO_AUTOR')->toArray()) }}">
    <meta name="author"
        content="{{ $libro->autores->pluck('NOMBRE_AUTOR', 'APELLIDO_AUTOR')->map(fn($nombre, $apellido) => $nombre . ' ' . $apellido)->join(', ') }}">

    {{-- Open Graph (Facebook, LinkedIn) --}}
    <meta property="og:type" content="book">
    <meta property="og:title" content="{{ $libro->TITULO_LIBRO }}">
    <meta property="og:description" content="{{ Str::limit($libro->CAPITULO_LIBRO ?: 'Libro académico: ' . $libro->TITULO_LIBRO, 160) }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:site_name" content="Blog Investigación">
    @if ($libro->URL_IMAGEN_LIBRO)
        <meta property="og:image" content="{{ asset('storage/' . ltrim($libro->URL_IMAGEN_LIBRO, '/')) }}">
        <meta property="og:image:alt" content="Portada del libro {{ $libro->TITULO_LIBRO }}">
    @endif

    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $libro->TITULO_LIBRO }}">
    <meta name="twitter:description" content="{{ Str::limit($libro->CAPITULO_LIBRO ?: 'Libro académico: ' . $libro->TITULO_LIBRO, 160) }}">
    @if ($libro->URL_IMAGEN_LIBRO)
        <meta name="twitter:image" content="{{ asset('storage/' . ltrim($libro->URL_IMAGEN_LIBRO, '/')) }}">
    @endif

    {{-- Académico/Investigación --}}
    <meta name="citation_title" content="{{ $libro->TITULO_LIBRO }}">
    <meta name="citation_publication_date"
        content="{{ \Carbon\Carbon::parse($libro->FECHA_LIBRO)->format('Y/m/d') }}">
    <meta name="citation_publisher" content="{{ $libro->EDITORIAL_LIBRO }}">
    <meta name="citation_isbn" content="{{ $libro->ISBN_LIBRO }}">
    @if ($libro->DOI_LIBRO)
        <meta name="citation_doi" content="{{ $libro->DOI_LIBRO }}">
    @endif
    @foreach ($libro->autores as $autor)
        <meta name="citation_author" content="{{ $autor->APELLIDO_AUTOR }}, {{ $autor->NOMBRE_AUTOR }}">
    @endforeach
    @if ($libro->URL_LIBRO)
        <meta name="citation_pdf_url" content="{{ asset('storage/' . ltrim($libro->URL_LIBRO, '/')) }}">
    @endif

@endsection

@section('content')
    {{-- Barra de progreso profesional --}}
    <div class="progress-executive" id="progressBar"></div>

    {{-- Navegación profesional --}}
    <a href="{{ route('libros.index') }}" class="floating-nav-executive d-none d-md-block">
        <i class="fas fa-arrow-left me-2"></i>Volver al índice
    </a>

    {{-- Hero Section Profesional --}}
    <section class="hero-executive">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="mb-3 d-md-none">
                        <a href="{{ route('libros.index') }}" class="floating-nav-executive">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>

                    <h1 class="display-5 fw-bold mb-4 text-white fade-in-professional">
                        {{ $libro->TITULO_LIBRO }}
                    </h1>

                    <p class="subtitle-executive text-white opacity-90 mb-4 fade-in-professional">
                        Libro académico publicado por {{ $libro->EDITORIAL_LIBRO }}
                        @if ($libro->CAPITULO_LIBRO)
                            - {{ $libro->CAPITULO_LIBRO }}
                        @endif
                    </p>

                    {{-- Estadísticas rápidas --}}
                    <div class="stats-grid fade-in-professional">
                        <div class="stat-item">
                            <span class="stat-number">{{ $libro->CAPITULO_LIBRO ? 'Cap.' : 'Completo' }}</span>
                            <div class="stat-label">Tipo de contenido</div>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ number_format($libro->VISTA_LIBRO ?? 0) }}</span>
                            <div class="stat-label">Visualizaciones</div>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ number_format($libro->DESCARGA_LIBRO ?? 0) }}</span>
                            <div class="stat-label">Descargas</div>
                        </div>
                        <div class="stat-item">
                            <span
                                class="stat-number">{{ \Carbon\Carbon::parse($libro->FECHA_LIBRO)->format('Y') }}</span>
                            <div class="stat-label">Año publicación</div>
                        </div>
                    </div>
                </div>

                @if ($libro->URL_IMAGEN_LIBRO)
                    <div class="col-lg-4 text-center slide-in-professional">
                        <div class="research-image-executive">
                            <img src="{{ asset($libro->URL_IMAGEN_LIBRO) }}"
                                alt="Portada del libro {{ $libro->TITULO_LIBRO }}" 
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
                            @foreach ($libro->autores as $autor)
                                <span class="author-executive">
                                    <i class="fas fa-user me-2"></i>
                                    {{ $autor->NOMBRE_AUTOR }} {{ $autor->APELLIDO_AUTOR }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Información del Contenido --}}
                @if ($libro->CAPITULO_LIBRO)
                    <div class="executive-card fade-in-professional">
                        <div class="card-body p-4">
                            <h4 class="section-title-executive">
                                <i class="fas fa-bookmark"></i>Capítulo
                            </h4>
                            <div class="academic-text-executive">
                                {{ $libro->CAPITULO_LIBRO }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="executive-card fade-in-professional">
                        <div class="card-body p-4">
                            <h4 class="section-title-executive">
                                <i class="fas fa-book"></i>Contenido
                            </h4>
                            <div class="academic-text-executive">
                                Este es el libro completo: <strong>{{ $libro->TITULO_LIBRO }}</strong>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- DOI (si existe) --}}
                @if ($libro->DOI_LIBRO)
                    <div class="executive-card fade-in-professional">
                        <div class="card-body p-4">
                            <h5 class="section-title-executive">
                                <i class="fas fa-fingerprint"></i>Digital Object Identifier (DOI)
                            </h5>
                            <div class="doi-container">
                                <code class="bg-light px-3 py-2 rounded d-inline-block">
                                    {{ $libro->DOI_LIBRO }}
                                </code>
                                <a href="https://doi.org/{{ $libro->DOI_LIBRO }}" 
                                   target="_blank" 
                                   class="btn btn-sm custom-button ms-2">
                                    <i class="fas fa-external-link-alt me-1"></i>Ver en DOI.org
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Cita Académica --}}
                <div class="executive-card fade-in-professional">
                    <div class="card-body p-4">
                        <h5 class="section-title-executive">
                            <i class="fas fa-quote-left"></i>Cita Recomendada
                        </h5>
                        <div class="citation-executive">
                            @php
                                $autores = $libro->autores
                                    ->map(function ($autor) {
                                        return $autor->APELLIDO_AUTOR . ', ' . substr($autor->NOMBRE_AUTOR, 0, 1) . '.';
                                    })
                                    ->join(', ');
                                $year = \Carbon\Carbon::parse($libro->FECHA_LIBRO)->format('Y');
                            @endphp
                            {{ $autores }} ({{ $year }}). {{ $libro->TITULO_LIBRO }}.
                            @if ($libro->CAPITULO_LIBRO)
                                <em>{{ $libro->CAPITULO_LIBRO }}</em>.
                            @endif
                            {{ $libro->EDITORIAL_LIBRO }}.
                            ISBN: {{ $libro->ISBN_LIBRO }}.
                            @if ($libro->DOI_LIBRO)
                                DOI: {{ $libro->DOI_LIBRO }}.
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Compartir --}}
                <div class="executive-card fade-in-professional">
                    <div class="card-body p-4">
                        <h5 class="section-title-executive">
                            <i class="fas fa-share-alt"></i>Compartir Libro
                        </h5>
                        <div class="d-flex justify-content-start flex-wrap">
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($libro->TITULO_LIBRO) }}&url={{ urlencode(request()->url()) }}"
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
                            <a href="mailto:?subject={{ urlencode($libro->TITULO_LIBRO) }}&body={{ urlencode('Le comparto este libro académico: ' . request()->url()) }}"
                                class="share-executive" title="Compartir por email">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Sidebar Informativo --}}
            <aside class="col-lg-4 col-md-12">
                {{-- Información del Libro --}}
                <div class="executive-card slide-in-professional">
                    <div class="card-body p-4">
                        <h4 class="section-title-executive">
                            <i class="fas fa-info-circle"></i>Información del Libro
                        </h4>

                        <table class="metadata-table">
                            <tbody>
                                <tr>
                                    <td>
                                        <i class="fas fa-calendar me-2"></i>Fecha
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($libro->FECHA_LIBRO)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fas fa-barcode me-2"></i>ISBN
                                    </td>
                                    <td>
                                        <code class="bg-light px-2 py-1 rounded">{{ $libro->ISBN_LIBRO }}</code>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fas fa-building me-2"></i>Editorial
                                    </td>
                                    <td class="fw-medium">{{ $libro->EDITORIAL_LIBRO }}</td>
                                </tr>
                                @if ($libro->DOI_LIBRO)
                                    <tr>
                                        <td>
                                            <i class="fas fa-fingerprint me-2"></i>DOI
                                        </td>
                                        <td>
                                            <small class="font-monospace">{{ $libro->DOI_LIBRO }}</small>
                                        </td>
                                    </tr>
                                @endif
                                @if ($libro->CAPITULO_LIBRO)
                                    <tr>
                                        <td>
                                            <i class="fas fa-bookmark me-2"></i>Capítulo
                                        </td>
                                        <td class="fst-italic">{{ $libro->CAPITULO_LIBRO }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        {{-- Descarga Profesional --}}
                        @if (!empty($libro->URL_LIBRO))
                            <div class="download-executive">
                                <h6 class="mb-3 text-primary fw-bold">
                                    <i class="fas fa-download me-2"></i>Documento Completo
                                </h6>

                                @php
                                    $filePath = public_path(ltrim($libro->URL_LIBRO, '/'));
                                @endphp

                                @if (file_exists($filePath))
                                    <a href="{{ route('libros.download', $libro->ID_LIBRO) }}"
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

                {{-- Libros Relacionados --}}
                <div class="executive-card slide-in-professional">
                    <div class="card-body p-4">
                        <h6 class="section-title-executive">
                            <i class="fas fa-sitemap"></i>Libros Relacionados
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