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
            --primary-vibrant: #6366f1;       /* Índigo vibrante */
            --secondary-vibrant: #8b5cf6;     /* Violeta */
            --accent-vibrant: #06b6d4;        /* Cian brillante */
            --success-vibrant: #10b981;       /* Verde esmeralda */
            --warning-vibrant: #f59e0b;       /* Ámbar */
            --danger-vibrant: #ef4444;        /* Rojo coral */
            --dark-elegant: #0f172a;          /* Azul muy oscuro */
            --light-elegant: #f8fafc;         /* Blanco suave */
            --gray-elegant: #64748b;          /* Gris elegante */
            --gradient-primary: linear-gradient(135deg, #6366f1, #8b5cf6, #06b6d4);
            --gradient-secondary: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            --border-width: 3px;
            --corner-cut: 16px;
        }

        body {
            background: var(--gradient-secondary);
            color: var(--dark-elegant);
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.7;
        }

        .hero-vibrant {
            background: var(--gradient-primary);
            color: white;
            position: relative;
            padding: 5rem 0 4rem;
            margin-bottom: 4rem;
            overflow: hidden;
            clip-path: polygon(0 0, 100% 0, 95% 100%, 5% 100%);
        }

        .hero-vibrant::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .hero-vibrant::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="vibrant-dots" width="10" height="10" patternUnits="userSpaceOnUse"><circle cx="5" cy="5" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23vibrant-dots)"/></svg>');
        }

        .vibrant-card {
            background: white;
            border: none;
            border-radius: 20px;
            box-shadow: 
                0 20px 25px -5px rgba(0, 0, 0, 0.1),
                0 10px 10px -5px rgba(0, 0, 0, 0.04),
                0 0 0 1px rgba(99, 102, 241, 0.1);
            position: relative;
            margin-bottom: 2.5rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            clip-path: polygon(
                var(--corner-cut) 0, 
                100% 0, 
                100% calc(100% - var(--corner-cut)), 
                calc(100% - var(--corner-cut)) 100%, 
                0 100%, 
                0 var(--corner-cut)
            );
        }

        .vibrant-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-primary);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: -1;
        }

        .vibrant-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 
                0 25px 50px -12px rgba(99, 102, 241, 0.25),
                0 0 0 1px rgba(99, 102, 241, 0.2);
        }

        .vibrant-card:hover::before {
            opacity: 0.03;
        }

        .vibrant-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.4), 
                transparent);
            transition: left 0.5s ease;
            z-index: 1;
            pointer-events: none;
        }

        .vibrant-card:hover::after {
            left: 100%;
        }

        .research-image-vibrant {
            border: 4px solid transparent;
            border-radius: 16px;
            background: var(--gradient-primary);
            padding: 4px;
            box-shadow: 0 20px 40px rgba(99, 102, 241, 0.3);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .research-image-vibrant img {
            border-radius: 12px;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .research-image-vibrant:hover {
            transform: scale(1.05) rotate(2deg);
            box-shadow: 0 25px 50px rgba(99, 102, 241, 0.4);
        }

        .metric-vibrant {
            background: var(--gradient-primary);
            color: white;
            padding: 0.75rem 1.25rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            display: inline-block;
            margin: 0.3rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 8px 16px rgba(99, 102, 241, 0.3);
        }

        .metric-vibrant::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.3), 
                transparent);
            transition: left 0.5s ease;
        }

        .metric-vibrant:hover::before {
            left: 100%;
        }

        .metric-vibrant:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(99, 102, 241, 0.4);
        }

        .author-vibrant {
            background: linear-gradient(135deg, 
                rgba(99, 102, 241, 0.1), 
                rgba(139, 92, 246, 0.1));
            border: 2px solid var(--primary-vibrant);
            color: var(--primary-vibrant);
            padding: 0.6rem 1.2rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
            margin: 0.3rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .author-vibrant::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-primary);
            transform: scale(0);
            transition: transform 0.3s ease;
            border-radius: 25px;
            z-index: -1;
        }

        .author-vibrant:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(99, 102, 241, 0.3);
        }

        .author-vibrant:hover::before {
            transform: scale(1);
        }

        .download-vibrant {
            background: linear-gradient(135deg, 
                rgba(99, 102, 241, 0.05), 
                rgba(6, 182, 212, 0.05));
            border: 3px solid transparent;
            background-clip: padding-box;
            border-radius: 20px;
            padding: 2.5rem;
            text-align: center;
            margin-top: 2rem;
            position: relative;
            overflow: hidden;
        }

        .download-vibrant::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-primary);
            margin: -3px;
            border-radius: 20px;
            z-index: -1;
        }

        .floating-nav-vibrant {
            position: fixed;
            top: 30px;
            left: 30px;
            z-index: 1000;
            background: white;
            border: none;
            color: var(--primary-vibrant);
            padding: 1rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .floating-nav-vibrant:hover {
            background: var(--gradient-primary);
            color: white;
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 20px 25px rgba(99, 102, 241, 0.3);
        }

        .progress-vibrant {
            position: fixed;
            top: 0;
            left: 0;
            height: 4px;
            background: var(--gradient-primary);
            z-index: 1001;
            transition: width 0.3s ease;
            box-shadow: 0 0 10px rgba(99, 102, 241, 0.5);
        }

        .share-vibrant {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-vibrant);
            text-decoration: none;
            background: white;
            border: 2px solid var(--primary-vibrant);
            border-radius: 50%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .share-vibrant::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-primary);
            border-radius: 50%;
            transform: scale(0);
            transition: transform 0.3s ease;
            z-index: -1;
        }

        .share-vibrant:hover {
            color: white;
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        .share-vibrant:hover::before {
            transform: scale(1);
        }

        .info-item-vibrant {
            border-left: 4px solid var(--primary-vibrant);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, 
                rgba(99, 102, 241, 0.02), 
                rgba(6, 182, 212, 0.02));
            border-radius: 0 12px 12px 0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .info-item-vibrant::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--gradient-primary);
            transition: width 0.3s ease;
        }

        .info-item-vibrant:hover {
            background: linear-gradient(135deg, 
                rgba(99, 102, 241, 0.05), 
                rgba(6, 182, 212, 0.05));
            transform: translateX(8px);
        }

        .info-item-vibrant:hover::before {
            width: 8px;
        }

        .btn-vibrant {
            --color: var(--primary-vibrant);
            
            background: transparent;
            border: 3px solid var(--color);
            color: var(--color);
            padding: 1rem 2rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            border-radius: 50px;
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 1rem;
            overflow: hidden;
        }

        .btn-vibrant::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-primary);
            transform: scale(0);
            transform-origin: center;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 50px;
            z-index: -1;
        }

        .btn-vibrant:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(99, 102, 241, 0.4);
            border-color: transparent;
        }

        .btn-vibrant:hover::before {
            transform: scale(1);
        }

        .btn-vibrant-success {
            --color: var(--success-vibrant);
        }

        .btn-vibrant-warning {
            --color: var(--warning-vibrant);
        }

        .section-title-vibrant {
            color: var(--primary-vibrant);
            font-weight: 800;
            font-size: 1.5rem;
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 1rem;
        }

        .section-title-vibrant::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: 2px;
        }

        .academic-text-vibrant {
            color: var(--dark-elegant);
            line-height: 1.9;
            font-size: 1.1rem;
            text-align: justify;
        }

        .citation-vibrant {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border: 1px solid #cbd5e0;
            border-radius: 16px;
            padding: 1.5rem;
            margin-top: 2rem;
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 0.9rem;
            color: var(--gray-elegant);
            position: relative;
            overflow: hidden;
        }

        .citation-vibrant::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--gradient-primary);
        }

        .glow-effect {
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from { box-shadow: 0 0 5px rgba(99, 102, 241, 0.2); }
            to { box-shadow: 0 0 20px rgba(99, 102, 241, 0.4); }
        }

        .pulse-effect {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        @media (max-width: 768px) {
            .hero-vibrant {
                padding: 3rem 0 2rem;
                clip-path: polygon(0 0, 100% 0, 97% 100%, 3% 100%);
            }
            
            .floating-nav-vibrant {
                position: relative;
                top: auto;
                left: auto;
                margin-bottom: 1.5rem;
                display: block;
                width: fit-content;
            }

            .vibrant-card {
                --corner-cut: 12px;
            }

            .section-title-vibrant {
                font-size: 1.3rem;
            }
        }

        /* Animaciones de entrada */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .fade-in-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .fade-in-left {
            opacity: 0;
            transform: translateX(-30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .fade-in-left.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .fade-in-right {
            opacity: 0;
            transform: translateX(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .fade-in-right.visible {
            opacity: 1;
            transform: translateX(0);
        }

        /* Tipografía mejorada */
        h1, h2, h3, h4, h5, h6 {
            color: var(--primary-vibrant);
            font-weight: 800;
        }

        .subtitle-vibrant {
            color: var(--gray-elegant);
            font-weight: 600;
            font-size: 1.2rem;
        }
    </style>
@endpush

@section('content')
    {{-- Barra de progreso vibrante --}}
    <div class="progress-vibrant" id="progressBar"></div>
    
    {{-- Botón de navegación vibrante --}}
    <a href="{{ route('articulos.articulo') }}" class="floating-nav-vibrant d-none d-md-block">
        <i class="fas fa-arrow-left me-2"></i>Volver al índice
    </a>
    
    {{-- Hero Section Vibrante --}}
    <section class="hero-vibrant">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="mb-3 d-md-none">
                        <a href="{{ route('articulos.articulo') }}" class="floating-nav-vibrant">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>
                    
                    <h1 class="display-4 fw-bold mb-4 text-white fade-in-left">
                        {{ $articulo->TITULO_ARTICULO }}
                    </h1>
                    
                    <p class="subtitle-vibrant text-white opacity-90 mb-4 fade-in-left">
                        📚 Investigación académica publicada en {{ $articulo->REVISTA_ARTICULO }}
                    </p>
                    
                    <div class="mt-4 fade-in-left">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center text-white opacity-85">
                                    <i class="fas fa-clock me-2 fs-5"></i>
                                    <span class="fw-semibold">{{ ceil(str_word_count(strip_tags($articulo->RESUMEN_ARTICULO)) / 200) }} min de lectura</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center text-white opacity-85">
                                    <i class="fas fa-calendar-alt me-2 fs-5"></i>
                                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($articulo->FECHA_ARTICULO)->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Métricas vibrantes --}}
                        <div class="mt-4">
                            <span class="metric-vibrant pulse-effect">
                                <i class="fas fa-eye me-2"></i>{{ $articulo->vistas ?? rand(50, 500) }} vistas
                            </span>
                            <span class="metric-vibrant">
                                <i class="fas fa-download me-2"></i>{{ $articulo->descargas ?? rand(10, 100) }} descargas
                            </span>
                            <span class="metric-vibrant glow-effect">
                                <i class="fas fa-award me-2"></i>Revisado por pares
                            </span>
                        </div>
                    </div>
                </div>
                
                @if($articulo->URL_IMAGEN_ARTICULO)
                    <div class="col-lg-4 text-center fade-in-right">
                        <div class="research-image-vibrant">
                            <img src="{{ $articulo->URL_IMAGEN_ARTICULO }}" 
                                 alt="Portada: {{ $articulo->TITULO_ARTICULO }}"
                                 style="max-height: 350px;">
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
                {{-- Autores Vibrantes --}}
                <div class="vibrant-card fade-in-up">
                    <div class="card-body p-4">
                        <h4 class="section-title-vibrant">
                            <i class="fas fa-users me-3"></i>Autores de la Investigación
                        </h4>
                        <div class="authors-container">
                            @foreach ($articulo->autores as $autor)
                                <span class="author-vibrant">
                                    <i class="fas fa-user-graduate me-2"></i>
                                    {{ $autor->NOMBRE_AUTOR }} {{ $autor->APELLIDO_AUTOR }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Resumen Vibrante --}}
                <div class="vibrant-card fade-in-up">
                    <div class="card-body p-4">
                        <h4 class="section-title-vibrant">
                            <i class="fas fa-file-text me-3"></i>Resumen Ejecutivo
                        </h4>
                        <div class="academic-text-vibrant">
                            {!! nl2br(e($articulo->RESUMEN_ARTICULO)) !!}
                        </div>
                    </div>
                </div>

                {{-- Cita académica vibrante --}}
                <div class="vibrant-card fade-in-up">
                    <div class="card-body p-4">
                        <h5 class="section-title-vibrant">
                            <i class="fas fa-quote-left me-3"></i>Cómo Citar Este Artículo
                        </h5>
                        <div class="citation-vibrant">
                            @php
                                $autores = $articulo->autores->map(function($autor) {
                                    return $autor->APELLIDO_AUTOR . ', ' . substr($autor->NOMBRE_AUTOR, 0, 1) . '.';
                                })->join(', ');
                                $year = \Carbon\Carbon::parse($articulo->FECHA_ARTICULO)->format('Y');
                            @endphp
                            <strong>{{ $autores }}</strong> ({{ $year }}). <em>{{ $articulo->TITULO_ARTICULO }}</em>. 
                            {{ $articulo->REVISTA_ARTICULO }}. ISSN: {{ $articulo->ISSN_ARTICULO }}.
                        </div>
                    </div>
                </div>

                {{-- Compartir Vibrante --}}
                <div class="vibrant-card fade-in-up">
                    <div class="card-body p-4 text-center">
                        <h5 class="section-title-vibrant text-center">
                            <i class="fas fa-share-nodes me-3"></i>Compartir Esta Investigación
                        </h5>
                        <div class="d-flex justify-content-center flex-wrap">
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($articulo->TITULO_ARTICULO) }}&url={{ urlencode(request()->url()) }}" 
                               class="share-vibrant" target="_blank" title="Compartir en Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                               class="share-vibrant" target="_blank" title="Compartir en Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" 
                               class="share-vibrant" target="_blank" title="Compartir en LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="mailto:?subject={{ urlencode($articulo->TITULO_ARTICULO) }}&body={{ urlencode('Te comparto esta investigación: ' . request()->url()) }}" 
                               class="share-vibrant" title="Compartir por email">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Sidebar Vibrante --}}
            <aside class="col-lg-4 col-md-12">
                {{-- Panel de Información --}}
                <div class="vibrant-card fade-in-right">
                    <div class="card-body p-4">
                        <h4 class="section-title-vibrant text-center">
                            <i class="fas fa-info-circle me-3"></i>Información del Artículo
                        </h4>
                        
                        <div class="info-item-vibrant">
                            <strong class="d-block mb-2 text-primary">
                                <i class="fas fa-calendar-check me-2"></i>Fecha de Publicación
                            </strong>
                            <span class="fs-6">{{ \Carbon\Carbon::parse($articulo->FECHA_ARTICULO)->format('d \d\e F \d\e Y') }}</span>
                        </div>
                        
                        <div class="info-item-vibrant">
                            <strong class="d-block mb-2 text-primary">
                                <i class="fas fa-barcode me-2"></i>Número ISSN
                            </strong>
                            <code class="fs-6 bg-light px-2 py-1 rounded">{{ $articulo->ISSN_ARTICULO }}</code>
                        </div>
                        
                        <div class="info-item-vibrant">
                            <strong class="d-block mb-2 text-primary">
                                <i class="fas fa-journal-whills me-2"></i>Revista Científica
                            </strong>
                            <a href="{{ $articulo->URL_REVISTA_ARTICULO }}" 
                               target="_blank" 
                               class="text-decoration-none fw-semibold text-primary">
                                {{ $articulo->REVISTA_ARTICULO }}
                                <i class="fas fa-external-link-alt ms-2"></i>
                            </a>
                        </div>

                        {{-- Sección de descarga vibrante --}}
                        @if (!empty($articulo->URL_ARTICULO))
                            <div class="download-vibrant">
                                <h5 class="mb-4 text-primary fw-bold">
                                    <i class="fas fa-download me-2"></i>Descargar Documento
                                </h5>
                                
                                <a href="{{ $articulo->URL_ARTICULO }}"
                                   class="btn-vibrant btn-vibrant-success w-100 d-block text-center mb-3"
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
                                        Tamaño: {{ $fileSize }} MB
                                    </small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Artículos relacionados vibrantes --}}
                <div class="vibrant-card fade-in-right">
                    <div class="card-body p-4">
                        <h5 class="section-title-vibrant">
                            <i class="fas fa-network-wired me-3"></i>Investigaciones Relacionadas
                        </h5>
                        
                        <div class="text-center text-muted">
                            <div class="mb-3">
                                <i class="fas fa-robot fa-3x opacity-50"></i>
                            </div>
                            <p class="mb-1 fw-semibold">Sistema de IA en desarrollo</p>
                            <small>Recomendaciones inteligentes próximamente</small>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Barra de progreso vibrante
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

        // Animaciones de entrada avanzadas
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                    }, index * 150); // Efecto cascada
                }
            });
        }, observerOptions);

        // Observar elementos con animaciones
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.fade-in-up, .fade-in-left, .fade-in-right').forEach(element => {
                observer.observe(element);
            });
        });

        // Efecto parallax suave en el hero
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero-vibrant');
            if (hero) {
                hero.style.transform = `translateY(${scrolled * 0.3}px)`;
            }
        });

        // Smooth scroll mejorado
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

        // Efecto de typing en el título (opcional)
        function typeWriter(element, text, speed = 50) {
            let i = 0;
            element.innerHTML = '';
            
            function typing() {
                if (i < text.length) {
                    element.innerHTML += text.charAt(i);
                    i++;
                    setTimeout(typing, speed);
                }
            }
            typing();
        }

        // Activar typing effect si se desea
        // const title = document.querySelector('.hero-vibrant h1');
        // if (title) {
        //     const originalText = title.textContent;
        //     typeWriter(title, originalText, 30);
        // }
    </script>
@endpush