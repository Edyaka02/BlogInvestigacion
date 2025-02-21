<form method="GET" action="{{ $route }}" class="mb-4 filter-form">
    <div class="row">
        <div class="col-md-9">
            <div class="input-group mb-3 search-input-group">
                <span class="input-group-text search-icon-container" id="basic-addon1">
                    <i class="fas fa-search search-icon"></i>
                </span>
                <input type="text" name="search" class="form-control search-input" placeholder="Buscar..."
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dropend mb-3">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Filtrar
                </button>

                <div class="dropdown-menu p-4" aria-labelledby="dropdownMenuButton"
                    style="max-height: 300px; overflow-y: auto;">
                    <div class="mb-3">
                        <label class="form-label" style="cursor: pointer;"
                            onclick="toggleOptions(event, 'ordenarOptions')">Ordenar por</label>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <div id="ordenarOptions" class="options-container">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ordenar" value="titulo_asc"
                                    id="ordenarTituloAsc"
                                    {{ request()->query('ordenar') == 'titulo_asc' ? 'checked' : '' }}>
                                <label class="form-check-label" for="ordenarTituloAsc">
                                    Título (A-Z)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ordenar" value="titulo_desc"
                                    id="ordenarTituloDesc"
                                    {{ request()->query('ordenar') == 'titulo_desc' ? 'checked' : '' }}>
                                <label class="form-check-label" for="ordenarTituloDesc">
                                    Título (Z-A)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ordenar" value="fecha_desc"
                                    id="ordenarFechaDesc"
                                    {{ request()->query('ordenar', 'fecha_desc') == 'fecha_desc' ? 'checked' : '' }}>
                                <label class="form-check-label" for="ordenarFechaDesc">
                                    Más recientes
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ordenar" value="fecha_asc"
                                    id="ordenarFechaAsc"
                                    {{ request()->query('ordenar') == 'fecha_asc' ? 'checked' : '' }}>
                                <label class="form-check-label" for="ordenarFechaAsc">
                                    Más antiguos
                                </label>
                            </div>
                        </div>
                    </div>

                    @if (!empty($tiposArticulos))
                        <div class="mb-3">
                            <label class="form-label" style="cursor: pointer;"
                                onclick="toggleOptions(event, 'tipoOptions')">Por tipo</label>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <div id="tipoOptions" style="display: none;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tipo[]" value="Investigacion"
                                        id="tipoInvestigacion"
                                        {{ in_array('Investigacion', request()->query('tipo', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tipoInvestigacion">
                                        Investigación
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tipo[]" value="Divulgacion"
                                        id="tipoDivulgacion"
                                        {{ in_array('Divulgacion', request()->query('tipo', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tipoDivulgacion">
                                        Divulgación
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (!empty($years))
                        <div class="mb-3">
                            <label class="form-label" style="cursor: pointer;"
                                onclick="toggleOptions(event, 'anioOptions')">Por año</label>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <div id="anioOptions" style="display: none;">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="anio" value="todos"
                                        id="anioTodos"
                                        {{ request()->query('anio', 'todos') == 'todos' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="anioTodos">
                                        Todos los años
                                    </label>
                                </div>

                                @foreach ($years as $year)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="anio"
                                            value="{{ $year }}" id="anio{{ $year }}"
                                            {{ request()->query('anio') == $year ? 'checked' : '' }}>
                                        <label class="form-check-label" for="anio{{ $year }}">
                                            {{ $year }}
                                        </label>
                                    </div>
                                @endforeach

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="intervaloAniosCheckbox"
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
                    @endif

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-outline-primary">Aplicar Filtros</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
