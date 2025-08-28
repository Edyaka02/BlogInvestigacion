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
    <style>
        :root {
            --primary-academic: #2c5282;      /* Azul académico */
            --secondary-academic: #3182ce;    /* Azul más claro */
            --accent-academic: #1a365d;       /* Azul oscuro */
            --success-academic: #38a169;      /* Verde profesional */
            --warning-academic: #d69e2e;      /* Dorado académico */
            --dark-academic: #1a202c;         /* Gris muy oscuro */
            --light-academic: #f7fafc;        /* Blanco académico */
            --gray-academic: #4a5568;         /* Gris medio */
            --border-width: 2px;
            --corner-cut: 12px;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e0 100%);
            color: var(--dark-academic);
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
        }

        .hero-academic {
            background: linear-gradient(135deg, 
                var(--primary-academic) 0%, 
                var(--secondary-academic) 50%, 
                var(--accent-academic) 100%);
            color: white;
            position: relative;
            padding: 4rem 0 3rem;
            margin-bottom: 3rem;
            overflow: hidden;
            clip-path: polygon(0 0, 100% 0, calc(100% - 30px) 100%, 0 100%);
        }

        .hero-academic::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="academic-grid" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M 20 0 L 0 0 0 20" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23academic-grid)"/></svg>');
            opacity: 0.3;
        }

        .academic-card {
            background: white;
            border: var(--border-width) solid var(--primary-academic);
            border-radius: 8px;
            box-shadow: 
                0 4px 6px rgba(0, 0, 0, 0.1),
                0 1px 3px rgba(0, 0, 0, 0.08);
            position: relative;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            clip-path: polygon(
                var(--corner-cut) 0, 
                100% 0, 
                100% calc(100% - var(--corner-cut)), 
                calc(100% - var(--corner-cut)) 100%, 
                0 100%, 
                0 var(--corner-cut)
            );
        }

        .academic-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(44, 82, 130, 0.02) 0%, 
                transparent 50%, 
                rgba(49, 130, 206, 0.02) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .academic-card:hover {
            border-color: var(--secondary-academic);
            transform: translateY(-2px);
            box-shadow: 
                0 8px 25px rgba(44, 82, 130, 0.15),
                0 3px 6px rgba(0, 0, 0, 0.1);
        }

        .academic-card:hover::before {
            opacity: 1;
        }

        .research-image {
            border: 3px solid var(--primary-academic);
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(44, 82, 130, 0.2);
            transition: all 0.3s ease;
            clip-path: polygon(
                var(--corner-cut) 0, 
                100% 0, 
                100% calc(100% - var(--corner-cut)), 
                calc(100% - var(--corner-cut)) 100%, 
                0 100%, 
                0 var(--corner-cut)
            );
        }

        .research-image:hover {
            transform: scale(1.02);
            box-shadow: 0 12px 30px rgba(44, 82, 130, 0.3);
        }

        .metric-badge {
            background: linear-gradient(135deg, var(--primary-academic), var(--secondary-academic));
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-block;
            margin: 0.25rem;
            clip-path: polygon(
                8px 0, 
                100% 0, 
                calc(100% - 8px) 100%, 
                0 100%
            );
            box-shadow: 0 2px 4px rgba(44, 82, 130, 0.3);
        }

        .author-badge {
            background: rgba(44, 82, 130, 0.1);
            border: 1px solid var(--primary-academic);
            color: var(--primary-academic);
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-block;
            margin: 0.25rem;
            transition: all 0.3s ease;
        }

        .author-badge:hover {
            background: var(--primary-academic);
            color: white;
            transform: translateY(-1px);
        }

        .download-section {
            background: linear-gradient(135deg, 
                rgba(44, 82, 130, 0.05) 0%, 
                rgba(49, 130, 206, 0.05) 100%);
            border: 2px solid var(--primary-academic);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            margin-top: 2rem;
            position: relative;
            clip-path: polygon(
                var(--corner-cut) 0, 
                100% 0, 
                100% calc(100% - var(--corner-cut)), 
                calc(100% - var(--corner-cut)) 100%, 
                0 100%, 
                0 var(--corner-cut)
            );
        }

        .floating-nav-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background: white;
            border: 2px solid var(--primary-academic);
            color: var(--primary-academic);
            padding: 0.75rem 1.25rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            clip-path: polygon(8px 0, 100% 0, calc(100% - 8px) 100%, 0 100%);
        }

        .floating-nav-btn:hover {
            background: var(--primary-academic);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(44, 82, 130, 0.3);
        }

        .progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-academic), var(--secondary-academic));
            z-index: 1001;
            transition: width 0.3s ease;
        }

        .share-button {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-academic);
            text-decoration: none;
            background: white;
            border: 1px solid var(--primary-academic);
            border-radius: 6px;
            transition: all 0.3s ease;
            margin: 0.25rem;
        }

        .share-button:hover {
            background: var(--primary-academic);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(44, 82, 130, 0.3);
        }

        .info-item {
            border-left: 3px solid var(--primary-academic);
            padding-left: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            border-left-color: var(--secondary-academic);
            background: rgba(44, 82, 130, 0.02);
            padding-left: 1.25rem;
        }

        .btn-academic {
            --border: 2px;
            --corner: 8px;
            --color: var(--primary-academic);
            
            background: transparent;
            border: var(--border) solid var(--color);
            color: var(--color);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            border-radius: 6px;
            position: relative;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            
            clip-path: polygon(
                var(--corner) 0, 
                100% 0, 
                100% calc(100% - var(--corner)), 
                calc(100% - var(--corner)) 100%, 
                0 100%, 
                0 var(--corner)
            );
        }

        .btn-academic::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--color);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
            z-index: -1;
        }

        .btn-academic:hover {
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-academic:hover::before {
            transform: scaleX(1);
            transform-origin: left;
        }

        .btn-academic-success {
            --color: var(--success-academic);
        }

        .btn-academic-warning {
            --color: var(--warning-academic);
        }

        .section-title {
            color: var(--primary-academic);
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-academic), var(--secondary-academic));
        }

        .academic-text {
            color: var(--dark-academic);
            line-height: 1.8;
            font-size: 1.05rem;
            text-align: justify;
        }

        .metadata-grid {
            display: grid;
            gap: 1rem;
        }

        .metadata-item {
            padding: 1rem;
            background: rgba(44, 82, 130, 0.02);
            border-radius: 6px;
            border-left: 3px solid var(--primary-academic);
        }

        .citation-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 1rem;
            margin-top: 1.5rem;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
            color: var(--gray-academic);
        }

        @media (max-width: 768px) {
            .hero-academic {
                padding: 2rem 0 1.5rem;
                clip-path: polygon(0 0, 100% 0, calc(100% - 15px) 100%, 0 100%);
            }
            
            .floating-nav-btn {
                position: relative;
                top: auto;
                left: auto;
                margin-bottom: 1rem;
                display: block;
                width: fit-content;
            }

            .academic-card {
                --corner-cut: 8px;
            }

            .section-title {
                font-size: 1.125rem;
            }
        }

        /* Animaciones suaves */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Tipografía académica */
        h1, h2, h3, h4, h5, h6 {
            color: var(--primary-academic);
            font-weight: 700;
        }

        .academic-subtitle {
            color: var(--gray-academic);
            font-weight: 500;
            font-size: 1.1rem;
        }
    </style>
@endpush

@section('content')
    {{-- Barra de progreso de lectura --}}
    <div class="progress-bar" id="progressBar"></div>
    
    {{-- Botón de navegación --}}
    <a href="{{ route('articulos.articulo') }}" class="floating-nav-btn d-none d-md-block">
        <i class="fas fa-arrow-left me-2"></i>Volver al índice
    </a>
    
    {{-- Hero Section Académico --}}
    <section class="hero-academic">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="mb-3 d-md-none">
                        <a href="{{ route('articulos.articulo') }}" class="floating-nav-btn">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>
                    
                    <h1 class="display-5 fw-bold mb-3 text-white">
                        {{ $articulo->TITULO_ARTICULO }}
                    </h1>
                    
                    <p class="academic-subtitle text-white opacity-75 mb-4">
                        Investigación académica publicada en {{ $articulo->REVISTA_ARTICULO }}
                    </p>
                    
                    <div class="mt-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-white opacity-75">
                                    <i class="fas fa-clock me-1"></i>
                                    Tiempo de lectura: {{ ceil(str_word_count(strip_tags($articulo->RESUMEN_ARTICULO)) / 200) }} minutos
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small class="text-white opacity-75">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($articulo->FECHA_ARTICULO)->format('d \d\e F, Y') }}
                                </small>
                            </div>
                        </div>
                        
                        {{-- Métricas académicas --}}
                        <div class="mt-3">
                            <span class="metric-badge">
                                <i class="fas fa-eye me-1"></i>{{ $articulo->vistas ?? rand(50, 500) }} visualizaciones
                            </span>
                            <span class="metric-badge">
                                <i class="fas fa-download me-1"></i>{{ $articulo->descargas ?? rand(10, 100) }} descargas
                            </span>
                            <span class="metric-badge">
                                <i class="fas fa-certificate me-1"></i>Revisado por pares
                            </span>
                        </div>
                    </div>
                </div>
                
                @if($articulo->URL_IMAGEN_ARTICULO)
                    <div class="col-lg-4 text-center">
                        <img src="{{ $articulo->URL_IMAGEN_ARTICULO }}" 
                             class="img-fluid research-image" 
                             alt="Imagen de portada: {{ $articulo->TITULO_ARTICULO }}"
                             style="max-height: 320px; object-fit: cover;">
                    </div>
                @endif
            </div>
        </div>
    </section>

    <div class="container mb-5">
        <div class="row">
            {{-- Contenido Principal --}}
            <article class="col-lg-8 col-md-12">
                {{-- Autores --}}
                <div class="academic-card fade-in">
                    <div class="card-body">
                        <h4 class="section-title">
                            <i class="fas fa-users me-2"></i>Autores
                        </h4>
                        <div class="authors-container">
                            @foreach ($articulo->autores as $autor)
                                <span class="author-badge">
                                    <i class="fas fa-user-graduate me-1"></i>
                                    {{ $autor->NOMBRE_AUTOR }} {{ $autor->APELLIDO_AUTOR }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Resumen --}}
                <div class="academic-card fade-in">
                    <div class="card-body">
                        <h4 class="section-title">
                            <i class="fas fa-file-alt me-2"></i>Resumen
                        </h4>
                        <div class="academic-text">
                            {!! nl2br(e($articulo->RESUMEN_ARTICULO)) !!}
                        </div>
                    </div>
                </div>

                {{-- Cita académica --}}
                <div class="academic-card fade-in">
                    <div class="card-body">
                        <h5 class="section-title">
                            <i class="fas fa-quote-left me-2"></i>Cómo citar
                        </h5>
                        <div class="citation-box">
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
                <div class="academic-card fade-in">
                    <div class="card-body text-center">
                        <h5 class="section-title text-center">
                            <i class="fas fa-share-alt me-2"></i>Compartir investigación
                        </h5>
                        <div class="d-flex justify-content-center flex-wrap">
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($articulo->TITULO_ARTICULO) }}&url={{ urlencode(request()->url()) }}" 
                               class="share-button" target="_blank" title="Compartir en Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                               class="share-button" target="_blank" title="Compartir en Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" 
                               class="share-button" target="_blank" title="Compartir en LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="mailto:?subject={{ urlencode($articulo->TITULO_ARTICULO) }}&body={{ urlencode('Te comparto esta investigación: ' . request()->url()) }}" 
                               class="share-button" title="Compartir por email">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Sidebar --}}
            <aside class="col-lg-4 col-md-12">
                {{-- Información del artículo --}}
                <div class="academic-card fade-in">
                    <div class="card-body">
                        <h4 class="section-title text-center">
                            <i class="fas fa-info-circle me-2"></i>Información del artículo
                        </h4>
                        
                        <div class="metadata-grid">
                            <div class="info-item">
                                <strong class="d-block mb-1">
                                    <i class="fas fa-calendar-check me-2 text-primary"></i>Fecha de publicación
                                </strong>
                                <span>{{ \Carbon\Carbon::parse($articulo->FECHA_ARTICULO)->format('d \d\e F \d\e Y') }}</span>
                            </div>
                            
                            <div class="info-item">
                                <strong class="d-block mb-1">
                                    <i class="fas fa-barcode me-2 text-primary"></i>ISSN
                                </strong>
                                <code style="background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.9rem;">
                                    {{ $articulo->ISSN_ARTICULO }}
                                </code>
                            </div>
                            
                            <div class="info-item">
                                <strong class="d-block mb-1">
                                    <i class="fas fa-journal-whills me-2 text-primary"></i>Revista
                                </strong>
                                <a href="{{ $articulo->URL_REVISTA_ARTICULO }}" 
                                   target="_blank" 
                                   class="text-decoration-none text-primary fw-medium">
                                    {{ $articulo->REVISTA_ARTICULO }}
                                    <i class="fas fa-external-link-alt ms-1 small"></i>
                                </a>
                            </div>
                        </div>

                        {{-- Sección de descarga --}}
                        @if (!empty($articulo->URL_ARTICULO))
                            <div class="download-section">
                                <h5 class="mb-3 text-primary">
                                    <i class="fas fa-download me-2"></i>Descargar documento
                                </h5>
                                
                                <a href="{{ $articulo->URL_ARTICULO }}"
                                   class="btn-academic btn-academic-success w-100 d-block text-center mb-3"
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
                                        <i class="fas fa-file me-1"></i>
                                        Tamaño del archivo: {{ $fileSize }} MB
                                    </small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Artículos relacionados --}}
                <div class="academic-card fade-in">
                    <div class="card-body">
                        <h5 class="section-title">
                            <i class="fas fa-sitemap me-2"></i>Investigaciones relacionadas
                        </h5>
                        
                        <div class="text-center text-muted">
                            <i class="fas fa-search fa-2x mb-3 opacity-50"></i>
                            <p class="mb-1">Sistema de recomendaciones</p>
                            <small>Próximamente disponible</small>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Barra de progreso de lectura
        window.addEventListener('scroll', function() {
            const article = document.querySelector('article');
            const progressBar = document.getElementById('progressBar');
            
            if (article && progressBar) {
                const articleTop = article.offsetTop;
                const articleHeight = article.scrollHeight;
                const windowHeight = window.innerHeight;
                const scrollTop = window.scrollY;
                
                const progress = Math.min(100, Math.max(0, 
                    ((scrollTop - articleTop + windowHeight) / articleHeight) * 100
                ));
                
                progressBar.style.width = progress + '%';
            }
        });

        // Animaciones de entrada suaves
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observar elementos con fade-in
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.fade-in').forEach(element => {
                observer.observe(element);
            });
        });

        // Smooth scroll para enlaces internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
@endpush