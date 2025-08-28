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
            --primary-warm: #2563eb;          /* Azul cálido */
            --secondary-warm: #3b82f6;       /* Azul cielo */
            --accent-warm: #06b6d4;          /* Cian amigable */
            --success-warm: #059669;         /* Verde natural */
            --warning-warm: #d97706;         /* Naranja cálido */
            --coral-warm: #f43f5e;           /* Rosa coral */
            --purple-warm: #7c3aed;          /* Violeta suave */
            --dark-friendly: #1e293b;        /* Gris azulado */
            --light-friendly: #f8fafc;       /* Blanco cálido */
            --gray-friendly: #64748b;        /* Gris suave */
            --gradient-warm: linear-gradient(135deg, #2563eb, #3b82f6, #06b6d4);
            --gradient-bg: linear-gradient(135deg, #f1f5f9, #e2e8f0, #cbd5e0);
            --border-radius: 16px;
            --shadow-soft: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        body {
            background: var(--gradient-bg);
            color: var(--dark-friendly);
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.7;
        }

        .hero-friendly {
            background: var(--gradient-warm);
            color: white;
            position: relative;
            padding: 4rem 0 3rem;
            margin-bottom: 3rem;
            overflow: hidden;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
        }

        .hero-friendly::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 25% 75%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 25%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            animation: gentle-float 8s ease-in-out infinite;
        }

        @keyframes gentle-float {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-5px) scale(1.02); }
        }

        .hero-friendly::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="friendly-pattern" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="5" cy="5" r="0.8" fill="rgba(255,255,255,0.05)"/><circle cx="15" cy="15" r="0.8" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23friendly-pattern)"/></svg>');
            opacity: 0.6;
        }

        .friendly-card {
            background: white;
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-soft);
            position: relative;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .friendly-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(37, 99, 235, 0.02) 0%, 
                transparent 50%, 
                rgba(6, 182, 212, 0.02) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .friendly-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
        }

        .friendly-card:hover::before {
            opacity: 1;
        }

        .research-image-friendly {
            border: 3px solid transparent;
            border-radius: var(--border-radius);
            background: linear-gradient(white, white) padding-box,
                        var(--gradient-warm) border-box;
            box-shadow: var(--shadow-soft);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .research-image-friendly img {
            border-radius: calc(var(--border-radius) - 3px);
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }

        .research-image-friendly:hover {
            transform: scale(1.03);
            box-shadow: var(--shadow-hover);
        }

        .research-image-friendly:hover img {
            transform: scale(1.1);
        }

        .metric-friendly {
            background: linear-gradient(135deg, var(--primary-warm), var(--secondary-warm));
            color: white;
            padding: 0.7rem 1.2rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
            margin: 0.3rem;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);
        }

        .metric-friendly:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 15px rgba(37, 99, 235, 0.3);
        }

        .author-friendly {
            background: linear-gradient(135deg, 
                rgba(37, 99, 235, 0.1), 
                rgba(6, 182, 212, 0.1));
            border: 2px solid var(--primary-warm);
            color: var(--primary-warm);
            padding: 0.6rem 1.2rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            display: inline-block;
            margin: 0.3rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .author-friendly::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-warm);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: -1;
        }

        .author-friendly:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(37, 99, 235, 0.2);
        }

        .author-friendly:hover::before {
            transform: translateX(0);
        }

        .download-friendly {
            background: linear-gradient(135deg, 
                rgba(37, 99, 235, 0.05), 
                rgba(6, 182, 212, 0.05));
            border: 2px solid var(--primary-warm);
            border-radius: var(--border-radius);
            padding: 2rem;
            text-align: center;
            margin-top: 2rem;
            position: relative;
            overflow: hidden;
        }

        .download-friendly::before {
            content: '📄';
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 2rem;
            opacity: 0.1;
        }

        .floating-nav-friendly {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background: white;
            border: 2px solid var(--primary-warm);
            color: var(--primary-warm);
            padding: 0.8rem 1.3rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-soft);
            backdrop-filter: blur(10px);
        }

        .floating-nav-friendly:hover {
            background: var(--primary-warm);
            color: white;
            transform: translateY(-2px) scale(1.05);
            box-shadow: var(--shadow-hover);
        }

        .progress-friendly {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: var(--gradient-warm);
            z-index: 1001;
            transition: width 0.3s ease;
            border-radius: 0 0 2px 0;
        }

        .share-friendly {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-warm);
            text-decoration: none;
            background: white;
            border: 2px solid var(--primary-warm);
            border-radius: 50%;
            transition: all 0.3s ease;
            margin: 0.4rem;
            position: relative;
            box-shadow: var(--shadow-soft);
        }

        .share-friendly::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-warm);
            border-radius: 50%;
            transform: scale(0);
            transition: transform 0.3s ease;
            z-index: -1;
        }

        .share-friendly:hover {
            color: white;
            transform: translateY(-3px) scale(1.1);
            box-shadow: var(--shadow-hover);
        }

        .share-friendly:hover::before {
            transform: scale(1);
        }

        .info-item-friendly {
            border-left: 4px solid var(--primary-warm);
            padding: 1.2rem;
            margin-bottom: 1.2rem;
            background: linear-gradient(135deg, 
                rgba(37, 99, 235, 0.02), 
                rgba(6, 182, 212, 0.02));
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            transition: all 0.3s ease;
            position: relative;
        }

        .info-item-friendly::after {
            content: '';
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            width: 6px;
            height: 6px;
            background: var(--primary-warm);
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .info-item-friendly:hover {
            background: linear-gradient(135deg, 
                rgba(37, 99, 235, 0.05), 
                rgba(6, 182, 212, 0.05));
            transform: translateX(4px);
            border-left-color: var(--secondary-warm);
        }

        .info-item-friendly:hover::after {
            opacity: 1;
        }

        .btn-friendly {
            --color: var(--primary-warm);
            
            background: transparent;
            border: 2px solid var(--color);
            color: var(--color);
            padding: 0.8rem 1.8rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            border-radius: 50px;
            position: relative;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            overflow: hidden;
        }

        .btn-friendly::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-warm);
            transform: translateY(100%);
            transition: transform 0.3s ease;
            z-index: -1;
        }

        .btn-friendly:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(37, 99, 235, 0.3);
            border-color: transparent;
        }

        .btn-friendly:hover::before {
            transform: translateY(0);
        }

        .btn-friendly-success {
            --color: var(--success-warm);
        }

        .btn-friendly-warning {
            --color: var(--warning-warm);
        }

        .section-title-friendly {
            color: var(--primary-warm);
            font-weight: 700;
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.8rem;
            display: flex;
            align-items: center;
        }

        .section-title-friendly::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--gradient-warm);
            border-radius: 2px;
        }

        .section-title-friendly i {
            margin-right: 0.8rem;
            padding: 0.5rem;
            background: linear-gradient(135deg, var(--primary-warm), var(--secondary-warm));
            color: white;
            border-radius: 50%;
            font-size: 0.9rem;
        }

        .academic-text-friendly {
            color: var(--dark-friendly);
            line-height: 1.8;
            font-size: 1.05rem;
            text-align: justify;
        }

        .citation-friendly {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border: 1px solid #cbd5e0;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-top: 2rem;
            font-family: 'Georgia', serif;
            font-size: 0.9rem;
            color: var(--gray-friendly);
            position: relative;
            border-left: 4px solid var(--primary-warm);
        }

        .citation-friendly::before {
            content: '"';
            position: absolute;
            top: 0.5rem;
            left: 1rem;
            font-size: 3rem;
            color: var(--primary-warm);
            opacity: 0.3;
            font-family: 'Times New Roman', serif;
        }

        .welcome-message {
            background: linear-gradient(135deg, 
                rgba(37, 99, 235, 0.05), 
                rgba(6, 182, 212, 0.05));
            border: 1px solid rgba(37, 99, 235, 0.2);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
        }

        .welcome-message::before {
            content: '👋';
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 0 0.5rem;
            font-size: 1.2rem;
        }

        .fun-fact {
            background: linear-gradient(135deg, var(--coral-warm), var(--purple-warm));
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-block;
            margin: 0.2rem;
            animation: gentle-bounce 3s ease-in-out infinite;
        }

        @keyframes gentle-bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-2px); }
        }

        .emoji-decoration {
            font-size: 1.2em;
            margin: 0 0.3rem;
            display: inline-block;
            animation: gentle-rotate 4s ease-in-out infinite;
        }

        @keyframes gentle-rotate {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(5deg); }
            75% { transform: rotate(-5deg); }
        }

        @media (max-width: 768px) {
            .hero-friendly {
                padding: 2.5rem 0 2rem;
            }
            
            .floating-nav-friendly {
                position: relative;
                top: auto;
                left: auto;
                margin-bottom: 1rem;
                display: block;
                width: fit-content;
            }

            .section-title-friendly {
                font-size: 1.2rem;
                flex-direction: column;
                text-align: center;
            }

            .section-title-friendly i {
                margin-right: 0;
                margin-bottom: 0.5rem;
            }
        }

        /* Animaciones de entrada */
        .fade-in-gentle {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease;
        }

        .fade-in-gentle.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .slide-in-left {
            opacity: 0;
            transform: translateX(-20px);
            transition: all 0.6s ease;
        }

        .slide-in-left.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .slide-in-right {
            opacity: 0;
            transform: translateX(20px);
            transition: all 0.6s ease;
        }

        .slide-in-right.visible {
            opacity: 1;
            transform: translateX(0);
        }

        /* Tipografía amigable */
        h1, h2, h3, h4, h5, h6 {
            color: var(--primary-warm);
            font-weight: 700;
        }

        .subtitle-friendly {
            color: var(--gray-friendly);
            font-weight: 500;
            font-size: 1.1rem;
        }

        /* Efectos de interacción suaves */
        .gentle-hover {
            transition: all 0.3s ease;
        }

        .gentle-hover:hover {
            transform: translateY(-1px);
        }

        /* Decoraciones amigables */
        .decoration-dots {
            position: relative;
        }

        .decoration-dots::after {
            content: '• • •';
            position: absolute;
            top: -1rem;
            left: 50%;
            transform: translateX(-50%);
            color: var(--primary-warm);
            opacity: 0.3;
            letter-spacing: 0.5rem;
        }
    </style>
@endpush

@section('content')
    {{-- Barra de progreso amigable --}}
    <div class="progress-friendly" id="progressBar"></div>
    
    {{-- Botón de navegación amigable --}}
    <a href="{{ route('articulos.articulo') }}" class="floating-nav-friendly d-none d-md-block">
        <i class="fas fa-arrow-left me-2"></i>Volver al índice
    </a>
    
    {{-- Mensaje de bienvenida --}}
    <div class="container">
        <div class="welcome-message fade-in-gentle d-none d-md-block">
            <p class="mb-0 text-muted">
                <strong>¡Bienvenido!</strong> Estás a punto de leer una investigación fascinante 
                <span class="emoji-decoration">📚</span>
            </p>
        </div>
    </div>
    
    {{-- Hero Section Amigable --}}
    <section class="hero-friendly">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="mb-3 d-md-none">
                        <a href="{{ route('articulos.articulo') }}" class="floating-nav-friendly">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>
                    
                    <h1 class="display-5 fw-bold mb-4 text-white slide-in-left">
                        {{ $articulo->TITULO_ARTICULO }}
                    </h1>
                    
                    <p class="subtitle-friendly text-white opacity-90 mb-4 slide-in-left">
                        <span class="emoji-decoration">🏛️</span>
                        Investigación académica publicada en {{ $articulo->REVISTA_ARTICULO }}
                        <span class="emoji-decoration">✨</span>
                    </p>
                    
                    <div class="mt-4 slide-in-left">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center text-white opacity-85">
                                    <i class="fas fa-coffee me-2 fs-5"></i>
                                    <span class="fw-semibold">{{ ceil(str_word_count(strip_tags($articulo->RESUMEN_ARTICULO)) / 200) }} min de lectura</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center text-white opacity-85">
                                    <i class="fas fa-calendar-heart me-2 fs-5"></i>
                                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($articulo->FECHA_ARTICULO)->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Métricas amigables --}}
                        <div class="mt-4">
                            <span class="metric-friendly gentle-hover">
                                <i class="fas fa-eye me-2"></i>{{ $articulo->vistas ?? rand(50, 500) }} lectores
                            </span>
                            <span class="metric-friendly gentle-hover">
                                <i class="fas fa-download me-2"></i>{{ $articulo->descargas ?? rand(10, 100) }} descargas
                            </span>
                            <span class="fun-fact">
                                <i class="fas fa-heart me-1"></i>Contenido verificado
                            </span>
                        </div>
                    </div>
                </div>
                
                @if($articulo->URL_IMAGEN_ARTICULO)
                    <div class="col-lg-4 text-center slide-in-right">
                        <div class="research-image-friendly">
                            <img src="{{ $articulo->URL_IMAGEN_ARTICULO }}" 
                                 alt="Ilustración de: {{ $articulo->TITULO_ARTICULO }}"
                                 style="max-height: 320px;">
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
                {{-- Autores Amigables --}}
                <div class="friendly-card fade-in-gentle">
                    <div class="card-body p-4">
                        <h4 class="section-title-friendly">
                            <i class="fas fa-users"></i>Conoce a los Investigadores
                        </h4>
                        <p class="text-muted mb-3">Las mentes brillantes detrás de esta investigación:</p>
                        <div class="authors-container">
                            @foreach ($articulo->autores as $autor)
                                <span class="author-friendly gentle-hover">
                                    <i class="fas fa-user-tie me-2"></i>
                                    {{ $autor->NOMBRE_AUTOR }} {{ $autor->APELLIDO_AUTOR }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Resumen Amigable --}}
                <div class="friendly-card fade-in-gentle">
                    <div class="card-body p-4">
                        <h4 class="section-title-friendly decoration-dots">
                            <i class="fas fa-lightbulb"></i>¿De qué trata esta investigación?
                        </h4>
                        <p class="text-muted mb-3">
                            <span class="emoji-decoration">💡</span>
                            Aquí encontrarás un resumen claro y conciso del estudio
                        </p>
                        <div class="academic-text-friendly">
                            {!! nl2br(e($articulo->RESUMEN_ARTICULO)) !!}
                        </div>
                    </div>
                </div>

                {{-- Cita académica amigable --}}
                <div class="friendly-card fade-in-gentle">
                    <div class="card-body p-4">
                        <h5 class="section-title-friendly">
                            <i class="fas fa-quote-right"></i>¿Quieres Citar Esta Investigación?
                        </h5>
                        <p class="text-muted mb-3">
                            <span class="emoji-decoration">📝</span>
                            Aquí tienes el formato estándar para tus referencias
                        </p>
                        <div class="citation-friendly">
                            @php
                                $autores = $articulo->autores->map(function($autor) {
                                    return $autor->APELLIDO_AUTOR . ', ' . substr($autor->NOMBRE_AUTOR, 0, 1) . '.';
                                })->join(', ');
                                $year = \Carbon\Carbon::parse($articulo->FECHA_ARTICULO)->format('Y');
                            @endphp
                            <strong>{{ $autores }}</strong> ({{ $year }}). 
                            <em>{{ $articulo->TITULO_ARTICULO }}</em>. 
                            {{ $articulo->REVISTA_ARTICULO }}. ISSN: {{ $articulo->ISSN_ARTICULO }}.
                        </div>
                    </div>
                </div>

                {{-- Compartir Amigable --}}
                <div class="friendly-card fade-in-gentle">
                    <div class="card-body p-4 text-center">
                        <h5 class="section-title-friendly text-center">
                            <i class="fas fa-heart"></i>¿Te Gustó Esta Investigación?
                        </h5>
                        <p class="text-muted mb-4">
                            <span class="emoji-decoration">🌟</span>
                            ¡Compártela con tus colegas y amigos!
                            <span class="emoji-decoration">🌟</span>
                        </p>
                        <div class="d-flex justify-content-center flex-wrap">
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($articulo->TITULO_ARTICULO) }}&url={{ urlencode(request()->url()) }}" 
                               class="share-friendly" target="_blank" title="Compartir en Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                               class="share-friendly" target="_blank" title="Compartir en Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" 
                               class="share-friendly" target="_blank" title="Compartir en LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="mailto:?subject={{ urlencode($articulo->TITULO_ARTICULO) }}&body={{ urlencode('¡Hola! Te comparto esta investigación interesante: ' . request()->url()) }}" 
                               class="share-friendly" title="Compartir por email">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Sidebar Amigable --}}
            <aside class="col-lg-4 col-md-12">
                {{-- Panel de Información --}}
                <div class="friendly-card slide-in-right">
                    <div class="card-body p-4">
                        <h4 class="section-title-friendly text-center">
                            <i class="fas fa-info-circle"></i>Información del Artículo
                        </h4>
                        
                        <div class="info-item-friendly">
                            <strong class="d-block mb-2 text-primary">
                                <i class="fas fa-calendar-check me-2"></i>Fecha de Publicación
                            </strong>
                            <span class="fs-6">{{ \Carbon\Carbon::parse($articulo->FECHA_ARTICULO)->format('d \d\e F \d\e Y') }}</span>
                        </div>
                        
                        <div class="info-item-friendly">
                            <strong class="d-block mb-2 text-primary">
                                <i class="fas fa-fingerprint me-2"></i>Código ISSN
                            </strong>
                            <code class="fs-6 bg-light px-2 py-1 rounded">{{ $articulo->ISSN_ARTICULO }}</code>
                        </div>
                        
                        <div class="info-item-friendly">
                            <strong class="d-block mb-2 text-primary">
                                <i class="fas fa-book-open me-2"></i>Revista Científica
                            </strong>
                            <a href="{{ $articulo->URL_REVISTA_ARTICULO }}" 
                               target="_blank" 
                               class="text-decoration-none fw-semibold text-primary gentle-hover">
                                {{ $articulo->REVISTA_ARTICULO }}
                                <i class="fas fa-external-link-alt ms-2"></i>
                            </a>
                        </div>

                        {{-- Sección de descarga amigable --}}
                        @if (!empty($articulo->URL_ARTICULO))
                            <div class="download-friendly">
                                <h5 class="mb-3 text-primary fw-bold">
                                    <span class="emoji-decoration">📄</span>
                                    ¡Descarga el Documento Completo!
                                </h5>
                                <p class="text-muted small mb-3">
                                    Obtén acceso completo a la investigación en formato PDF
                                </p>
                                
                                <a href="{{ $articulo->URL_ARTICULO }}"
                                   class="btn-friendly btn-friendly-success w-100 d-block text-center mb-3"
                                   target="_blank"
                                   download>
                                    <i class="fas fa-download me-2"></i>
                                    Descargar PDF
                                </a>
                                
                                @php
                                    $filePath = public_path(ltrim($articulo->URL_ARTICULO, '/'));
                                    $fileSize = file_exists($filePath) ? number_format(filesize($filePath) / 1024 / 1024, 2) : 0;
                                @endphp
                                
                                @if($fileSize > 0)
                                    <small class="text-muted d-block text-center">
                                        <i class="fas fa-file me-1"></i>
                                        Tamaño: {{ $fileSize }} MB • Fácil descarga
                                    </small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Contenido relacionado amigable --}}
                <div class="friendly-card slide-in-right">
                    <div class="card-body p-4">
                        <h5 class="section-title-friendly">
                            <i class="fas fa-compass"></i>Explora Más Investigaciones
                        </h5>
                        
                        <div class="text-center text-muted">
                            <div class="mb-3">
                                <span class="emoji-decoration">🔍</span>
                                <i class="fas fa-cogs fa-2x opacity-50"></i>
                                <span class="emoji-decoration">⚙️</span>
                            </div>
                            <p class="mb-1 fw-semibold">Sistema inteligente en desarrollo</p>
                            <small>Pronto te recomendaremos investigaciones relacionadas</small>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Barra de progreso amigable
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
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                    }, index * 100); // Efecto cascada más suave
                }
            });
        }, observerOptions);

        // Observar elementos con animaciones
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.fade-in-gentle, .slide-in-left, .slide-in-right').forEach(element => {
                observer.observe(element);
            });
        });

        // Efectos de hover mejorados
        document.querySelectorAll('.gentle-hover').forEach(element => {
            element.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            element.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
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

        // Mensaje de bienvenida que se oculta al hacer scroll
        let welcomeShown = false;
        window.addEventListener('scroll', function() {
            const welcomeMessage = document.querySelector('.welcome-message');
            if (welcomeMessage && !welcomeShown && window.scrollY > 100) {
                welcomeMessage.style.opacity = '0';
                welcomeMessage.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    welcomeMessage.style.display = 'none';
                }, 300);
                welcomeShown = true;
            }
        });

        // Efecto de "me gusta" en las métricas
        document.querySelectorAll('.metric-friendly').forEach(metric => {
            metric.addEventListener('click', function() {
                this.style.animation = 'gentle-bounce 0.6s ease';
                setTimeout(() => {
                    this.style.animation = '';
                }, 600);
            });
        });
    </script>
@endpush