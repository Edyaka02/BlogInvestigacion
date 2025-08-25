{{-- filepath: c:\laragon\www\BlogInvestigacion\resources\views\components\no-encontrado.blade.php --}}
<div class="text-center mt-5">
    <div class="mb-4">
        <img src="{{ asset('assets/svg/Not-Found.svg') }}" alt="No se encontraron resultados" class="img-fluid opacity-50"
            style="max-width: 200px; filter: grayscale(100%);">
    </div>
    <h2 class="text-muted">{{ 'No se encontraron resultados' }}</h2>
    <p class="text-muted">{{ 'Intenta ajustar tus criterios de búsqueda.' }}</p>

    {{-- @if (isset($mostrarSugerencias) && $mostrarSugerencias)
        <div class="card border-0 bg-light mx-auto mt-4" style="max-width: 400px;">
            <div class="card-body p-4">
                <h6 class="card-title text-muted mb-3">Sugerencias:</h6>
                <ul class="list-unstyled text-start mb-0 small text-muted">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Verifica la ortografía</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Usa términos más generales</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Prueba con sinónimos</li>
                    <li><i class="fas fa-check text-success me-2"></i>Reduce el número de filtros</li>
                </ul>
            </div>
        </div>
    @endif

    @if (isset($mostrarBoton) && $mostrarBoton)
        <div class="mt-4">
            <button type="button" class="btn custom-button custom-button-ver" onclick="location.reload()">
                <i class="fas fa-refresh me-2"></i>Recargar página
            </button>
        </div>
    @endif --}}
</div>
