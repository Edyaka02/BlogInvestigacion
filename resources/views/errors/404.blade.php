{{-- resources/views/errors/404.blade.php --}}
@extends('components.public.publicLayout')

@section('title', 'Página no encontrada - 404')

@section('content')
    <div class="error-state-overlay">
        <div class="error-state-modal">
            {{-- 🎯 USAR TU CSS DE ERROR STATE --}}
            <div class="error-icon-wrapper">
                <i class="fas fa-search error-icon"></i>
                <div class="error-pulses">
                    <div class="pulse pulse-1"></div>
                    <div class="pulse pulse-2"></div>
                </div>
            </div>
            
            <h1 class="error-title">¡Oops! Página no encontrada</h1>
            
            <p class="error-description">
                Lo sentimos, la página que estás buscando no existe o ha sido movida. 
                Es posible que el enlace esté roto o que hayas escrito mal la URL.
            </p>
            
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                {{-- 🎯 USAR TUS BOTONES PERSONALIZADOS --}}
                <a href="{{ route('inicio') }}" class="custom-button">
                    <i class="fas fa-home me-2"></i>
                    <span class="btn-text">Ir al inicio</span>
                </a>
                
                <a href="javascript:history.back()" class="custom-button custom-button-warning">
                    <i class="fas fa-arrow-left me-2"></i>
                    <span class="btn-text">Volver atrás</span>
                </a>
            </div>
            
            <div class="mt-4">
                <small class="text-muted">
                    Si crees que esto es un error, por favor 
                    <a href="mailto:contacto@bloginvestigacion.com" class="text-decoration-none">contáctanos</a>
                </small>
            </div>
        </div>
    </div>
@endsection