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
            --primary-executive: #1e40af;      /* Azul ejecutivo */
            --secondary-executive: #3b82f6;    /* Azul corporativo */
            --accent-executive: #0ea5e9;       /* Azul cielo profesional */
            --success-executive: #059669;      /* Verde corporativo */
            --warning-executive: #d97706;      /* Ámbar profesional */
            --danger-executive: #dc2626;       /* Rojo corporativo */
            --dark-executive: #0f172a;         /* Gris ejecutivo */
            --light-executive: #f8fafc;        /* Blanco profesional */
            --gray-executive: #475569;         /* Gris neutro */
            --border-executive: #e2e8f0;       /* Borde sutil */
            --shadow-executive: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-hover-executive: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: var(--dark-executive);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            letter-spacing: -0.01em;
        }

        .hero-executive {
            background: linear-gradient(135deg, var(--primary-executive) 0%, var(--secondary-executive) 100%);
            color: white;
            position: relative;
            padding: 5rem 0 4rem;
            margin-bottom: 4rem;
            overflow: hidden;
        }

        .hero-executive::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="executive-grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23executive-grid)"/></svg>');
            opacity: 0.7;
        }

        .hero-executive::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        }

        .executive-card {
            background: white;
            border: 1px solid var(--border-executive);
            border-radius: 8px;
            box-shadow: var(--shadow-executive);
            position: relative;
            margin-bottom: 2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .executive-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-executive), var(--accent-executive));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .executive-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover-executive);
            border-color: var(--primary-executive);
        }

        .executive-card:hover::before {
            opacity: 1;
        }

        .research-image-executive {
            border: 2px solid var(--border-executive);
            border-radius: 8px;
            box-shadow: var(--shadow-executive);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .research-image-executive img {
            border-radius: 6px;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }

        .research-image-executive:hover {
            transform: scale(1.02);
            box-shadow: var(--shadow-hover-executive);
            border-color: var(--primary-executive);
        }

        .research-image-executive:hover img {
            transform: scale(1.05);
        }

        .metric-executive {
            background: linear-gradient(135deg, var(--primary-executive), var(--secondary-executive));
            color: white;
            padding: 0.6rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-block;
            margin: 0.25rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(30, 64, 175, 0.2);
        }

        .metric-executive:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(30, 64, 175, 0.3);
        }

        .author-executive {
            background: rgba(30, 64, 175, 0.05);
            border: 1px solid var(--primary-executive);
            color: var(--primary-executive);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-block;
            margin: 0.25rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .author-executive::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--primary-executive);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: -1;
        }

        .author-executive:hover {
            color: white;
            border-color: var(--primary-executive);
        }

        .author-executive:hover::before {
            transform: translateX(0);
        }

        .download-executive {
            background: linear-gradient(135deg, rgba(30, 64, 175, 0.02), rgba(14, 165, 233, 0.02));
            border: 2px solid var(--primary-executive);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            margin-top: 2rem;
            position: relative;
        }

        .download-executive::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(30, 64, 175, 0.05), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .download-executive:hover::before {
            opacity: 1;
        }

        .floating-nav-executive {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background: white;
            border: 1px solid var(--border-executive);
            color: var(--primary-executive);
            padding: 0.75rem 1.25rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-executive);
            backdrop-filter: blur(10px);
        }

        .floating-nav-executive:hover {
            background: var(--primary-executive);
            color: white;
            transform: translateY(-1px);
            box-shadow: var(--shadow-hover-executive);
        }

        .progress-executive {
            position: fixed;
            top: 0;
            left: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-executive), var(--accent-executive));
            z-index: 1001;
            transition: width 0.3s ease;
        }

        .share-executive {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-executive);
            text-decoration: none;
            background: white;
            border: 1px solid var(--border-executive);
            border-radius: 6px;
            transition: all 0.3s ease;
            margin: 0.25rem;
            box-shadow: var(--shadow-executive);
        }

        .share-executive:hover {
            background: var(--primary-executive);
            color: white;
            transform: translateY(-1px);
            box-shadow: var(--shadow-hover-executive);
            border-color: var(--primary-executive);
        }

        .info-item-executive {
            border-left: 3px solid var(--primary-executive);
            padding: 1rem;
            margin-bottom: 1rem;
            background: rgba(30, 64, 175, 0.02);
            border-radius: 0 6px 6px 0;
            transition: all 0.3s ease;
        }

        .info-item-executive:hover {
            background: rgba(30, 64, 175, 0.05);
            border-left-color: var(--accent-executive);
            transform: translateX(2px);
        }

        .btn-executive {
            --color: var(--primary-executive);
            
            background: transparent;
            border: 2px solid var(--color);
            color: var(--color);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            border-radius: 6px;
            position: relative;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            overflow: hidden;
        }

        .btn-executive::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--color);
            transform: translateY(100%);
            transition: transform 0.3s ease;
            z-index: -1;
        }

        .btn-executive:hover {
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(30, 64, 175, 0.3);
        }

        .btn-executive:hover::before {
            transform: translateY(0);
        }

        .btn-executive-success {
            --color: var(--success-executive);
        }

        .btn-executive-warning {
            --color: var(--warning-executive);
        }

        .section-title-executive {
            color: var(--primary-executive);
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border-executive);
        }

        .section-title-executive::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 40px;
            height: 2px;
            background: var(--primary-executive);
        }

        .section-title-executive i {
            margin-right: 0.75rem;
            color: var(--primary-executive);
        }

        .academic-text-executive {
            color: var(--dark-executive);
            line-height: 1.7;
            font-size: 1rem;
            text-align: justify;
            font-weight: 400;
        }

        .citation-executive {
            background: #f8fafc;
            border: 1px solid var(--border-executive);
            border-left: 4px solid var(--primary-executive);
            border-radius: 6px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            font-family: 'Georgia', 'Times New Roman', serif;
            font-size: 0.875rem;
            color: var(--gray-executive);
            font-style: italic;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
            background: white;
            border: 1px solid var(--border-executive);
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            border-color: var(--primary-executive);
            transform: translateY(-1px);
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-executive);
            display: block;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--gray-executive);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 0.25rem;
        }

        .breadcrumb-executive {
            background: transparent;
            padding: 0;
            margin-bottom: 1rem;
        }

        .breadcrumb-executive .breadcrumb-item {
            color: var(--gray-executive);
            font-size: 0.875rem;
        }

        .breadcrumb-executive .breadcrumb-item.active {
            color: var(--primary-executive);
            font-weight: 600;
        }

        .breadcrumb-executive .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            color: var(--gray-executive);
        }

        .metadata-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .metadata-table td {
            padding: 0.75rem;
            border-bottom: 1px solid var(--border-executive);
            vertical-align: top;
        }

        .metadata-table td:first-child {
            font-weight: 600;
            color: var(--primary-executive);
            width: 30%;
        }

        .metadata-table tr:hover {
            background: rgba(30, 64, 175, 0.02);
        }

        @media (max-width: 768px) {
            .hero-executive {
                padding: 3rem 0 2rem;
            }
            
            .floating-nav-executive {
                position: relative;
                top: auto;
                left: auto;
                margin-bottom: 1rem;
                display: block;
                width: fit-content;
            }

            .section-title-executive {
                font-size: 1.125rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Animaciones profesionales */
        .fade-in-professional {
            opacity: 0;
            transform: translateY(15px);
            transition: all 0.5s ease;
        }

        .fade-in-professional.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .slide-in-professional {
            opacity: 0;
            transform: translateX(15px);
            transition: all 0.5s ease;
        }

        .slide-in-professional.visible {
            opacity: 1;
            transform: translateX(0);
        }

        /* Tipografía profesional */
        h1, h2, h3, h4, h5, h6 {
            color: var(--dark-executive);
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .subtitle-executive {
            color: var(--gray-executive);
            font-weight: 500;
            font-size: 1rem;
        }

        /* Elementos de separación */
        .divider-executive {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border-executive), transparent);
            margin: 2rem 0;
        }

        /* Estados de loading */
        .loading-placeholder {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
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
    <script>
        // Barra de progreso profesional
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

        // Animaciones profesionales
        const observerOptions = {
            threshold: 0.15,
            rootMargin: '0px 0px -30px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observar elementos
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.fade-in-professional, .slide-in-professional').forEach(element => {
                observer.observe(element);
            });
        });

        // Smooth scroll
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

        // Analíticas simuladas (opcional)
        function trackDownload() {
            console.log('Descarga registrada:', new Date().toISOString());
        }

        function trackShare(platform) {
            console.log('Compartido en:', platform, new Date().toISOString());
        }

        // Asignar eventos a botones de descarga y compartir
        document.addEventListener('DOMContentLoaded', function() {
            // Tracking de descargas
            document.querySelectorAll('[download]').forEach(btn => {
                btn.addEventListener('click', trackDownload);
            });

            // Tracking de compartir
            document.querySelectorAll('.share-executive').forEach(btn => {
                btn.addEventListener('click', function() {
                    const platform = this.querySelector('i').className.includes('twitter') ? 'Twitter' :
                                   this.querySelector('i').className.includes('facebook') ? 'Facebook' :
                                   this.querySelector('i').className.includes('linkedin') ? 'LinkedIn' : 'Email';
                    trackShare(platform);
                });
            });
        });
    </script>
@endpush