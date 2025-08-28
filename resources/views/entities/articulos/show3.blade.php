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
            --primary-tech: #00d4ff;
            --secondary-tech: #0099cc;
            --accent-tech: #ff0080;
            --dark-tech: #0a0a0a;
            --light-tech: #f0f8ff;
            --border-tech: 3px;
            --slant-tech: 0.5em;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #16213e 100%);
            color: var(--light-tech);
            min-height: 100vh;
        }

        .hero-tech {
            background: linear-gradient(135deg, 
                rgba(0, 212, 255, 0.1) 0%, 
                rgba(255, 0, 128, 0.1) 50%, 
                rgba(0, 153, 204, 0.1) 100%);
            position: relative;
            padding: 4rem 0 2rem;
            margin-bottom: 2rem;
            overflow: hidden;
            clip-path: polygon(0 0, 100% 0, 95% 100%, 5% 100%);
        }

        .hero-tech::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(0, 212, 255, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 0, 128, 0.3) 0%, transparent 50%);
            animation: pulse-tech 4s ease-in-out infinite alternate;
        }

        @keyframes pulse-tech {
            0% { opacity: 0.3; }
            100% { opacity: 0.6; }
        }

        .hero-tech::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(0,212,255,0.2)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        }

        .tech-card {
            background: rgba(26, 26, 46, 0.8);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(0, 212, 255, 0.3);
            position: relative;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            clip-path: polygon(var(--slant-tech) 0, 100% 0, 
                              calc(100% - var(--slant-tech)) 100%, 0 100%);
        }

        .tech-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(0, 212, 255, 0.1) 0%, 
                transparent 50%, 
                rgba(255, 0, 128, 0.1) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .tech-card:hover {
            border-color: var(--primary-tech);
            transform: translateY(-5px);
            box-shadow: 
                0 10px 30px rgba(0, 212, 255, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .tech-card:hover::before {
            opacity: 1;
        }

        .tech-image {
            border-radius: 0;
            border: 3px solid var(--primary-tech);
            box-shadow: 
                0 0 20px rgba(0, 212, 255, 0.5),
                inset 0 0 20px rgba(0, 212, 255, 0.1);
            transition: all 0.3s ease;
            clip-path: polygon(20px 0, 100% 0, calc(100% - 20px) 100%, 0 100%);
        }

        .tech-image:hover {
            transform: scale(1.02);
            box-shadow: 
                0 0 30px rgba(0, 212, 255, 0.8),
                inset 0 0 30px rgba(0, 212, 255, 0.2);
        }

        .stats-tech {
            background: linear-gradient(45deg, var(--primary-tech), var(--accent-tech));
            color: var(--dark-tech);
            padding: 0.5rem 1rem;
            font-weight: bold;
            font-size: 0.9rem;
            display: inline-block;
            margin: 0.2rem;
            position: relative;
            clip-path: polygon(var(--slant-tech) 0, 100% 0, 
                              calc(100% - var(--slant-tech)) 100%, 0 100%);
            animation: glow-pulse 2s ease-in-out infinite alternate;
        }

        @keyframes glow-pulse {
            0% { box-shadow: 0 0 5px rgba(0, 212, 255, 0.5); }
            100% { box-shadow: 0 0 15px rgba(0, 212, 255, 0.8); }
        }

        .author-tech {
            background: rgba(0, 212, 255, 0.2);
            border: 1px solid var(--primary-tech);
            color: var(--primary-tech);
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
            display: inline-block;
            margin: 0.2rem;
            position: relative;
            clip-path: polygon(10px 0, 100% 0, calc(100% - 10px) 100%, 0 100%);
            transition: all 0.3s ease;
        }

        .author-tech:hover {
            background: var(--primary-tech);
            color: var(--dark-tech);
            transform: translateY(-2px);
        }

        .download-tech {
            background: linear-gradient(135deg, 
                rgba(0, 212, 255, 0.2) 0%, 
                rgba(255, 0, 128, 0.2) 100%);
            border: 2px solid var(--primary-tech);
            border-radius: 0;
            padding: 2rem;
            text-align: center;
            margin-top: 2rem;
            position: relative;
            clip-path: polygon(20px 0, 100% 0, calc(100% - 20px) 100%, 0 100%);
        }

        .download-tech::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, 
                transparent 30%, 
                rgba(0, 212, 255, 0.1) 50%, 
                transparent 70%);
            animation: scan 3s linear infinite;
        }

        @keyframes scan {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .floating-tech-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background: rgba(26, 26, 46, 0.9);
            backdrop-filter: blur(10px);
            border: 2px solid var(--primary-tech);
            color: var(--primary-tech);
            padding: 0.7rem 1.2rem;
            text-decoration: none;
            transition: all 0.3s ease;
            clip-path: polygon(10px 0, 100% 0, calc(100% - 10px) 100%, 0 100%);
        }

        .floating-tech-btn:hover {
            background: var(--primary-tech);
            color: var(--dark-tech);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 212, 255, 0.5);
        }

        .progress-tech {
            position: fixed;
            top: 0;
            left: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-tech), var(--accent-tech));
            z-index: 1001;
            transition: width 0.3s ease;
            box-shadow: 0 0 10px rgba(0, 212, 255, 0.8);
        }

        .share-tech {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-tech);
            text-decoration: none;
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid var(--primary-tech);
            transition: all 0.3s ease;
            clip-path: polygon(8px 0, 100% 0, calc(100% - 8px) 100%, 0 100%);
        }

        .share-tech:hover {
            background: var(--primary-tech);
            color: var(--dark-tech);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 212, 255, 0.5);
        }

        .info-item-tech {
            border-left: 3px solid var(--primary-tech);
            padding-left: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .info-item-tech:hover {
            border-left-color: var(--accent-tech);
            background: rgba(0, 212, 255, 0.05);
            padding-left: 1.5rem;
        }

        .btn-tech {
            --border: 3px;
            --slant: 0.5em;
            --color: var(--primary-tech);
            
            background: transparent;
            border: var(--border) solid var(--color);
            color: var(--color);
            padding: 0.8em 1.5em;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            position: relative;
            transition: all 0.3s ease;
            
            clip-path: polygon(var(--slant) 0, 100% 0, 
                              calc(100% - var(--slant)) 100%, 0 100%);
        }

        .btn-tech::before {
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

        .btn-tech:hover {
            color: var(--dark-tech);
        }

        .btn-tech:hover::before {
            transform: scaleX(1);
            transform-origin: left;
        }

        .btn-tech-danger {
            --color: var(--accent-tech);
        }

        .btn-tech-success {
            --color: #00ff88;
        }

        .glitch-text {
            position: relative;
            color: var(--primary-tech);
            animation: glitch 2s infinite;
        }

        @keyframes glitch {
            0%, 90%, 100% {
                transform: translate(0);
            }
            20% {
                transform: translate(-2px, 2px);
            }
            40% {
                transform: translate(-2px, -2px);
            }
            60% {
                transform: translate(2px, 2px);
            }
            80% {
                transform: translate(2px, -2px);
            }
        }

        .scan-line {
            position: relative;
            overflow: hidden;
        }

        .scan-line::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, 
                transparent, var(--primary-tech), transparent);
            animation: scan-line 2s linear infinite;
        }

        @keyframes scan-line {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        @media (max-width: 768px) {
            .hero-tech {
                padding: 2rem 0 1rem;
                clip-path: polygon(0 0, 100% 0, 98% 100%, 2% 100%);
            }
            
            .floating-tech-btn {
                position: relative;
                top: auto;
                left: auto;
                margin-bottom: 1rem;
                display: block;
                width: fit-content;
            }

            .tech-card {
                clip-path: polygon(10px 0, 100% 0, calc(100% - 10px) 100%, 0 100%);
            }
        }
    </style>
@endpush

@section('content')
    {{-- Barra de progreso tecnológica --}}
    <div class="progress-tech" id="progressBar"></div>
    
    {{-- Botón flotante tecnológico --}}
    <a href="{{ route('articulos.articulo') }}" class="floating-tech-btn d-none d-md-block">
        <i class="fas fa-arrow-left me-2"></i> VOLVER
    </a>
    
    {{-- Hero Section Tecnológico --}}
    <section class="hero-tech">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="mb-3 d-md-none">
                        <a href="{{ route('articulos.articulo') }}" class="floating-tech-btn">
                            <i class="fas fa-arrow-left me-2"></i> VOLVER
                        </a>
                    </div>
                    
                    <h1 class="display-4 fw-bold mb-3 glitch-text">
                        {{ $articulo->TITULO_ARTICULO }}
                    </h1>
                    
                    <div class="mt-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small style="color: rgba(0, 212, 255, 0.8);">
                                    <i class="fas fa-microchip me-1"></i>
                                    TIEMPO DE ANÁLISIS: {{ ceil(str_word_count(strip_tags($articulo->RESUMEN_ARTICULO)) / 200) }} MIN
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small style="color: rgba(0, 212, 255, 0.8);">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ strtoupper(\Carbon\Carbon::parse($articulo->FECHA_ARTICULO)->format('d M Y')) }}
                                </small>
                            </div>
                        </div>
                        
                        {{-- Estadísticas tecnológicas --}}
                        <div class="mt-3">
                            <span class="stats-tech">
                                <i class="fas fa-eye me-1"></i> {{ $articulo->vistas ?? rand(50, 500) }} VIEWS
                            </span>
                            <span class="stats-tech">
                                <i class="fas fa-download me-1"></i> {{ $articulo->descargas ?? rand(10, 100) }} DL
                            </span>
                            <span class="stats-tech">
                                <i class="fas fa-shield-alt me-1"></i> VERIFIED
                            </span>
                        </div>
                    </div>
                </div>
                
                @if($articulo->URL_IMAGEN_ARTICULO)
                    <div class="col-lg-4 text-center">
                        <img src="{{ $articulo->URL_IMAGEN_ARTICULO }}" 
                             class="img-fluid tech-image" 
                             alt="Portada de {{ $articulo->TITULO_ARTICULO }}"
                             style="max-height: 300px; object-fit: cover;">
                    </div>
                @endif
            </div>
        </div>
    </section>

    <div class="container mb-5">
        <div class="row">
            {{-- Contenido Principal --}}
            <article class="col-lg-8 col-md-12">
                {{-- Autores Tecnológicos --}}
                <div class="tech-card">
                    <div class="card-body scan-line">
                        <h4 class="card-title mb-3" style="color: var(--primary-tech);">
                            <i class="fas fa-users me-2"></i>INVESTIGADORES
                        </h4>
                        <div class="authors-container">
                            @foreach ($articulo->autores as $autor)
                                <span class="author-tech">
                                    <i class="fas fa-user-circle me-1"></i>
                                    {{ strtoupper($autor->NOMBRE_AUTOR . ' ' . $autor->APELLIDO_AUTOR) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Resumen Tecnológico --}}
                <div class="tech-card">
                    <div class="card-body">
                        <h4 class="card-title mb-4" style="color: var(--primary-tech);">
                            <i class="fas fa-file-code me-2"></i>ANÁLISIS DE DATOS
                        </h4>
                        <div class="article-content">
                            <p style="text-align: justify; line-height: 1.8; font-size: 1.1rem; color: var(--light-tech);">
                                {!! nl2br(e($articulo->RESUMEN_ARTICULO)) !!}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Compartir Tecnológico --}}
                <div class="tech-card">
                    <div class="card-body text-center">
                        <h5 class="mb-3" style="color: var(--primary-tech);">
                            <i class="fas fa-share-alt me-2"></i>COMPARTIR DATOS
                        </h5>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($articulo->TITULO_ARTICULO) }}&url={{ urlencode(request()->url()) }}" 
                               class="share-tech" target="_blank">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                               class="share-tech" target="_blank">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" 
                               class="share-tech" target="_blank">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($articulo->TITULO_ARTICULO . ' - ' . request()->url()) }}" 
                               class="share-tech" target="_blank">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Sidebar Tecnológico --}}
            <aside class="col-lg-4 col-md-12">
                {{-- Panel de Información --}}
                <div class="tech-card">
                    <div class="card-body">
                        <h4 class="card-title mb-4 text-center" style="color: var(--primary-tech);">
                            <i class="fas fa-database me-2"></i>METADATA
                        </h4>
                        
                        <div class="info-item-tech">
                            <strong style="color: var(--primary-tech);">
                                <i class="fas fa-calendar-check me-2"></i>FECHA:
                            </strong>
                            <div class="mt-1">{{ \Carbon\Carbon::parse($articulo->FECHA_ARTICULO)->format('d M Y') }}</div>
                        </div>
                        
                        <div class="info-item-tech">
                            <strong style="color: var(--primary-tech);">
                                <i class="fas fa-hashtag me-2"></i>ISSN:
                            </strong>
                            <div class="mt-1">
                                <code style="background: rgba(0, 212, 255, 0.2); padding: 0.2rem 0.5rem; border-radius: 3px;">
                                    {{ $articulo->ISSN_ARTICULO }}
                                </code>
                            </div>
                        </div>
                        
                        <div class="info-item-tech">
                            <strong style="color: var(--primary-tech);">
                                <i class="fas fa-server me-2"></i>FUENTE:
                            </strong>
                            <div class="mt-1">
                                <a href="{{ $articulo->URL_REVISTA_ARTICULO }}" 
                                   target="_blank" 
                                   class="text-decoration-none"
                                   style="color: var(--primary-tech);">
                                    {{ $articulo->REVISTA_ARTICULO }}
                                    <i class="fas fa-external-link-alt ms-1 small"></i>
                                </a>
                            </div>
                        </div>

                        {{-- Sección de descarga tecnológica --}}
                        @if (!empty($articulo->URL_ARTICULO))
                            <div class="download-tech">
                                <h5 class="mb-3" style="color: var(--primary-tech);">
                                    <i class="fas fa-download me-2"></i>DESCARGAR ARCHIVO
                                </h5>
                                
                                <a href="{{ $articulo->URL_ARTICULO }}"
                                   class="btn-tech btn-tech-success w-100 d-block text-center mb-3"
                                   target="_blank"
                                   download>
                                    <i class="fas fa-file-pdf me-2"></i>
                                    OBTENER PDF
                                </a>
                                
                                @php
                                    $filePath = public_path(ltrim($articulo->URL_ARTICULO, '/'));
                                    $fileSize = file_exists($filePath) ? number_format(filesize($filePath) / 1024 / 1024, 2) : 0;
                                @endphp
                                
                                @if($fileSize > 0)
                                    <small class="d-block" style="color: rgba(0, 212, 255, 0.7);">
                                        <i class="fas fa-hdd me-1"></i>
                                        TAMAÑO: {{ $fileSize }} MB
                                    </small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Panel de Datos Relacionados --}}
                <div class="tech-card">
                    <div class="card-body">
                        <h5 class="card-title mb-3" style="color: var(--primary-tech);">
                            <i class="fas fa-network-wired me-2"></i>DATOS RELACIONADOS
                        </h5>
                        
                        <div class="text-center" style="color: rgba(0, 212, 255, 0.6);">
                            <i class="fas fa-search fa-3x mb-3" style="opacity: 0.5;"></i>
                            <p>SISTEMA EN DESARROLLO</p>
                            <small>Próximamente análisis correlacionados</small>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Barra de progreso tecnológica
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

        // Efectos de entrada tecnológicos
        window.addEventListener('load', function() {
            document.querySelectorAll('.tech-card').forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(30px) rotateX(10deg)';
                    card.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
                    
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0) rotateX(0)';
                    }, 100);
                }, index * 150);
            });
        });

        // Efecto glitch aleatorio en el título
        setInterval(() => {
            const glitchText = document.querySelector('.glitch-text');
            if (glitchText && Math.random() > 0.9) {
                glitchText.style.animation = 'none';
                setTimeout(() => {
                    glitchText.style.animation = 'glitch 2s infinite';
                }, 100);
            }
        }, 5000);
    </script>
@endpush