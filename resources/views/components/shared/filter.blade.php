<form id="filtro-form" class="mb-4 filter-form">
    <!-- Offcanvas para los filtros -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasFiltros" aria-labelledby="offcanvasFiltrosLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasFiltrosLabel">Filtro</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body customCheckbox">
            <!-- Acordeón para las secciones de filtrado -->
            <div class="accordion" id="accordionFiltros">
                <!-- Sección: Ordenar por -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOrdenar">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOrdenar" aria-expanded="true" aria-controls="collapseOrdenar">
                            Ordenar por
                        </button>
                    </h2>
                    <div id="collapseOrdenar" class="accordion-collapse collapse show" aria-labelledby="headingOrdenar"
                        data-bs-parent="#accordionFiltros">
                        <div class="accordion-body">
                            <div class="form-check">
                                <input class="custom-radio" type="radio" name="ordenar" value="titulo_asc"
                                    id="ordenarTituloAsc"
                                    {{ request()->query('ordenar') == 'titulo_asc' ? 'checked' : '' }}>
                                <label class="form-check-label" for="ordenarTituloAsc">
                                    Título (A-Z)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="custom-radio" type="radio" name="ordenar" value="titulo_desc"
                                    id="ordenarTituloDesc"
                                    {{ request()->query('ordenar') == 'titulo_desc' ? 'checked' : '' }}>
                                <label class="form-check-label" for="ordenarTituloDesc">
                                    Título (Z-A)
                                </label>
                            </div>
                            @if (!empty($years))
                                <div class="form-check">
                                    <input class="custom-radio" type="radio" name="ordenar" value="fecha_desc"
                                        id="ordenarFechaDesc"
                                        {{ request()->query('ordenar', 'fecha_desc') == 'fecha_desc' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ordenarFechaDesc">
                                        Más recientes
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="custom-radio" type="radio" name="ordenar" value="fecha_asc"
                                        id="ordenarFechaAsc"
                                        {{ request()->query('ordenar') == 'fecha_asc' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ordenarFechaAsc">
                                        Más antiguos
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sección: Por tipo -->
                {{-- @if (!empty($config['tiposArticulos'])) --}}
                @if (!empty($tipos))
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTipo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTipo" aria-expanded="false" aria-controls="collapseTipo">
                                Por tipo
                            </button>
                        </h2>
                        <div id="collapseTipo" class="accordion-collapse collapse" aria-labelledby="headingTipo"
                            data-bs-parent="#accordionFiltros">
                            <div class="accordion-body">
                                @foreach ($tipos as $key => $value)
                                    <div class="form-check">
                                        <input class="form-check-input custom-checkbox" type="checkbox" name="tipo[]"
                                            value="{{ $key }}" id="tipo{{ $key }}"
                                            {{ in_array($key, request()->query('tipo', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tipo{{ $key }}">
                                            {{ $value }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Sección: Por año -->
                @if (!empty($years))
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingAnio">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseAnio" aria-expanded="false" aria-controls="collapseAnio">
                                Por año
                            </button>
                        </h2>
                        <div id="collapseAnio" class="accordion-collapse collapse" aria-labelledby="headingAnio"
                            data-bs-parent="#accordionFiltros">
                            <div class="accordion-body">
                                <div class="form-check">
                                    <input class="custom-radio" type="radio" name="anio" value="todos"
                                        id="anioTodos"
                                        {{ request()->query('anio', 'todos') == 'todos' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="anioTodos">
                                        Todos los años
                                    </label>
                                </div>
                                @foreach ($years as $year)
                                    {{-- <div class="form-check">
                                        <input class="form-check-input" type="radio" name="anio"
                                            value="{{ $year }}" id="anio{{ $year }}"
                                            {{ request()->query('anio') == $year ? 'checked' : '' }}>
                                        <label class="form-check-label" for="anio{{ $year }}">
                                            {{ $year }}
                                        </label>
                                    </div> --}}
                                    <div class="form-check">
                                        <input class="custom-radio" type="radio" name="anio"
                                            value="{{ $year }}" id="anio{{ $year }}"
                                            {{ request()->query('anio') == $year ? 'checked' : '' }}>
                                        <label class="form-check-label" for="anio{{ $year }}">
                                            {{ $year }}
                                        </label>
                                    </div>
                                @endforeach
                                <div class="form-check">
                                    <input class="custom-radio" type="radio" id="intervaloAniosCheckbox"
                                        name="anio" value="intervalo"
                                        {{ request()->query('anio') == 'intervalo' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="intervaloAniosCheckbox">
                                        Intervalo de años
                                    </label>
                                </div>
                                {{-- <div id="intervaloAnios" class="mt-2"
                                    style="display: {{ request()->query('anio') == 'intervalo' ? 'block' : 'none' }};">
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="anio_inicio" class="form-control flex-fill"
                                            placeholder="Inicio" value="{{ request()->query('anio_inicio') }}">
                                        <span class="input-group-text">-</span>
                                        <input type="number" name="anio_fin" class="form-control flex-fill" placeholder="Fin"
                                            value="{{ request()->query('anio_fin') }}">
                                    </div>
                                </div> --}}
                                <div id="intervaloAnios" class="intervalo-container"
                                    style="display: {{ request()->query('anio') == 'intervalo' ? 'block' : 'none' }};">
                                    <div class="intervalo-wrapper">
                                        <div class="input-group">
                                            <input type="number" name="anio_inicio" class="form-control"
                                                placeholder="Desde" value="{{ request()->query('anio_inicio') }}"
                                                min="1900" max="{{ date('Y') }}">
                                            <span class="input-group-text">—</span>
                                            <input type="number" name="anio_fin" class="form-control"
                                                placeholder="Hasta" value="{{ request()->query('anio_fin') }}"
                                                min="1900" max="{{ date('Y') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Botón para aplicar filtros -->
            <div class="d-flex justify-content-center mt-3">
                {{-- <button type="button" class="btn btn-outline-custom" id="aplicar-filtros-btn">Aplicar
                    Filtros</button> --}}
                <button type="button" class="btn custom-button custom-button-primario" id="aplicar-filtros-btn">
                    <i class="fa-solid fa-filter"></i> Aplicar
                </button>
            </div>
        </div>
    </div>
</form>


{{-- @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar filtros solo si el formulario de búsqueda y filtrado está presente
            if (document.querySelector('.filter-form')) {
                const anioRadios = document.querySelectorAll('input[name="anio"]');
                const intervaloAniosCheckbox = document.getElementById('intervaloAniosCheckbox');
                const intervaloAnios = document.getElementById('intervaloAnios');

                anioRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        if (this.value === 'intervalo') {
                            intervaloAnios.style.display = 'block';
                        } else {
                            intervaloAnios.style.display = 'none';
                            intervaloAnios.querySelectorAll('input').forEach(input => input.value =
                                '');
                        }
                    });
                });

                if (intervaloAniosCheckbox.checked) {
                    intervaloAnios.style.display = 'block';
                }
            }
        });
    </script>
@endpush --}}

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.filter-form')) {
                const anioRadios = document.querySelectorAll('input[name="anio"]');
                const intervaloAniosCheckbox = document.getElementById('intervaloAniosCheckbox');
                const intervaloAnios = document.getElementById('intervaloAnios');
                const anioInicioInput = document.querySelector('input[name="anio_inicio"]');
                const anioFinInput = document.querySelector('input[name="anio_fin"]');

                // ✅ CONFIGURACIÓN: Límites de año
                const YEAR_MIN = 1900;
                const YEAR_MAX = new Date().getFullYear();

                function setupYearInput(input, otherInput = null) {
                    // ✅ LIMITAR: Solo 4 dígitos
                    input.addEventListener('input', function() {
                        // Solo números
                        this.value = this.value.replace(/[^0-9]/g, '');

                        // Máximo 4 dígitos
                        if (this.value.length > 4) {
                            this.value = this.value.slice(0, 4);
                        }

                        // ✅ VALIDACIÓN: Rango de años
                        validateYearRange(this, otherInput);
                    });

                    // ✅ PREVENIR: Pegado de texto largo
                    input.addEventListener('paste', function(e) {
                        setTimeout(() => {
                            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);
                            validateYearRange(this, otherInput);
                        }, 10);
                    });

                    // ✅ PREVENIR: Teclas no numéricas
                    input.addEventListener('keydown', function(e) {
                        // Permitir teclas de control
                        const allowedKeys = [8, 9, 27, 13, 46, 37, 38, 39, 40];
                        if (allowedKeys.includes(e.keyCode) ||
                            (e.ctrlKey && [65, 67, 86, 88].includes(e.keyCode))) {
                            return;
                        }

                        // Solo números
                        if (e.keyCode < 48 || e.keyCode > 57) {
                            if (e.keyCode < 96 || e.keyCode > 105) {
                                e.preventDefault();
                            }
                        }

                        // Máximo 4 dígitos
                        if (this.value.length >= 4 && !allowedKeys.includes(e.keyCode)) {
                            e.preventDefault();
                        }
                    });
                }

                function validateYearRange(currentInput, otherInput) {
                    const value = parseInt(currentInput.value);

                    // ✅ VALIDAR: Año válido
                    if (currentInput.value.length === 4) {
                        if (value < YEAR_MIN || value > YEAR_MAX) {
                            currentInput.setCustomValidity(`El año debe estar entre ${YEAR_MIN} y ${YEAR_MAX}`);
                            currentInput.classList.add('is-invalid');
                        } else {
                            currentInput.setCustomValidity('');
                            currentInput.classList.remove('is-invalid');

                            // ✅ VALIDAR: Rango lógico (inicio < fin)
                            if (otherInput && otherInput.value.length === 4) {
                                const otherValue = parseInt(otherInput.value);
                                const isInicioInput = currentInput.name === 'anio_inicio';

                                if (isInicioInput && value > otherValue) {
                                    currentInput.setCustomValidity('El año de inicio debe ser menor al año de fin');
                                    currentInput.classList.add('is-invalid');
                                } else if (!isInicioInput && value < otherValue) {
                                    currentInput.setCustomValidity('El año de fin debe ser mayor al año de inicio');
                                    currentInput.classList.add('is-invalid');
                                } else {
                                    // Limpiar errores de ambos inputs si el rango es válido
                                    currentInput.setCustomValidity('');
                                    currentInput.classList.remove('is-invalid');
                                    otherInput.setCustomValidity('');
                                    otherInput.classList.remove('is-invalid');
                                }
                            }
                        }
                    } else if (currentInput.value.length > 0) {
                        currentInput.setCustomValidity('Ingrese un año de 4 dígitos');
                        currentInput.classList.add('is-invalid');
                    } else {
                        currentInput.setCustomValidity('');
                        currentInput.classList.remove('is-invalid');
                    }
                }

                // ✅ APLICAR: Configuración a los inputs de año
                if (anioInicioInput && anioFinInput) {
                    setupYearInput(anioInicioInput, anioFinInput);
                    setupYearInput(anioFinInput, anioInicioInput);
                }

                // ✅ EXISTENTE: Lógica de mostrar/ocultar intervalo
                anioRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        if (this.value === 'intervalo') {
                            intervaloAnios.style.display = 'block';
                        } else {
                            intervaloAnios.style.display = 'none';
                            // Limpiar valores y validaciones
                            [anioInicioInput, anioFinInput].forEach(input => {
                                if (input) {
                                    input.value = '';
                                    input.setCustomValidity('');
                                    input.classList.remove('is-invalid');
                                }
                            });
                        }
                    });
                });

                if (intervaloAniosCheckbox && intervaloAniosCheckbox.checked) {
                    intervaloAnios.style.display = 'block';
                }

                console.log('✅ Filtros de año inicializados con validaciones completas');
            }
        });
    </script>
@endpush
