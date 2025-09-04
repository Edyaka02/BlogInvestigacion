<!-- filepath: c:\laragon\www\BlogInvestigacion\resources\views\components\shared\emptyState.blade.php -->
<div id="empty-state-overlay" class="empty-state-overlay" style="display: none;">
    <div class="empty-state-modal">
        <!-- 🎯 IMAGEN SVG ANIMADA -->
        <div class="empty-svg-wrapper">
            <img src="{{ asset('assets/svg/not-found.svg') }}" 
                 alt="No se encontraron resultados" 
                 class="empty-svg"
                 loading="lazy">
            
            <!-- Ondas decorativas alrededor de la imagen -->
            <div class="svg-waves">
                <div class="wave wave-1"></div>
                <div class="wave wave-2"></div>
                <div class="wave wave-3"></div>
            </div>
        </div>
        
        <!-- Contenido dinámico mejorado -->
        <h3 class="empty-title">No se encontraron resultados</h3>
        <p class="empty-description">
            No hay elementos que coincidan con los criterios de búsqueda especificados. 
            Intenta ajustar los filtros o usar términos más generales.
        </p>
        
        <!-- Botón mejorado -->
        <button class="empty-action" onclick="clearAllFilters()" style="display: none;">
            <i class="fas fa-eraser me-2"></i>Limpiar filtros
        </button>
    </div>
</div>
