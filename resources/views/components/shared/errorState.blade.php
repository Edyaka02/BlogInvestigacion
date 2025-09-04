<!-- filepath: c:\laragon\www\BlogInvestigacion\resources\views\components\shared\errorState.blade.php -->
<div id="error-state-overlay" class="error-state-overlay" style="display: none;">
    <div class="error-state-modal">
        <!-- Icono animado de error mejorado -->
        <div class="error-icon-wrapper">
            <i class="fas fa-exclamation-triangle error-icon"></i>
            <div class="error-pulses">
                <div class="pulse pulse-1"></div>
                <div class="pulse pulse-2"></div>
            </div>
        </div>
        
        <!-- Contenido dinámico mejorado -->
        <h3 class="error-title">Error al cargar los datos</h3>
        <p class="error-description">
            Ocurrió un problema al obtener la información. Por favor, verifica tu conexión a internet e intenta nuevamente.
        </p>
        
        <!-- Botón de retry mejorado -->
        <button class="error-action">
            <i class="fas fa-redo me-2"></i>Reintentar carga
        </button>
    </div>
</div>

