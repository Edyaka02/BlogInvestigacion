<!-- filepath: c:\laragon\www\BlogInvestigacion\resources\views\components\bucador.blade.php -->
<form method="GET" action="{{ $route }}" class="mb-4 filter-form">
    <div class="row">
        <div class="col-md-10">
            <div class="input-group mb-3 search-input-group">
                <span class="input-group-text search-icon-container" id="basic-addon1">
                    <i class="fas fa-search search-icon"></i>
                </span>
                <input type="text" name="search" class="form-control search-input" placeholder="Buscar..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-search">
                    <i class="fas fa-search search-icon"></i>
                </button>
            </div>
        </div>

        <div class="col-md-2 text-md-end">
            <button class="btn btn-outline-custom w-100 w-md-auto" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasFiltros" aria-controls="offcanvasFiltros">
                <i class="fa-solid fa-sliders"></i>
                Filtrar
            </button>
        </div>
    </div>

    <!-- Offcanvas para los filtros -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasFiltros" aria-labelledby="offcanvasFiltrosLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasFiltrosLabel">Opciones de Filtro</h5>
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
                @if (!empty($config['tiposArticulos']))
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
                                @foreach ($config['tiposArticulos'] as $key => $value)
                                    <div class="form-check">
                                        <input class="form-check-input custom-checkbox" type="checkbox" name="tipo[]"
                                            value="{{ $key }}" id="tipo{{ $key }}"
                                            {{ in_array($key, request()->query('tipo', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tipo{{ $key }}">
                                            {{ $value }}
                                        </label>
                                    </div>
                                @endforeach
                                {{-- <div class="form-check">
                                    <input class="custom-checkbox" type="checkbox" name="tipo[]"
                                        value="Investigacion" id="tipoInvestigacion"
                                        {{ in_array('Investigacion', request()->query('tipo', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tipoInvestigacion">
                                        Investigación
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="custom-checkbox" type="checkbox" name="tipo[]"
                                        value="Divulgacion" id="tipoDivulgacion"
                                        {{ in_array('Divulgacion', request()->query('tipo', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tipoDivulgacion">
                                        Divulgación
                                    </label>
                                </div> --}}
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
                                <div id="intervaloAnios" class="mt-2"
                                    style="display: {{ request()->query('anio') == 'intervalo' ? 'block' : 'none' }};">
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="anio_inicio" class="form-control"
                                            placeholder="Inicio" value="{{ request()->query('anio_inicio') }}"
                                            style="max-width: 80px;">
                                        <span class="input-group-text">-</span>
                                        <input type="number" name="anio_fin" class="form-control" placeholder="Fin"
                                            value="{{ request()->query('anio_fin') }}" style="max-width: 80px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Botón para aplicar filtros -->
            <div class="d-flex justify-content-center mt-3">
                <button type="submit" class="btn btn-outline-custom">Aplicar Filtros</button>
            </div>
        </div>
    </div>
</form>
