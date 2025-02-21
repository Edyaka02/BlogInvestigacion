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
                            @if (!empty($years))
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
                            @endif
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

                    @if (!empty($config['tiposEventos']))
                        <div class="mb-3">
                            <label class="form-label" style="cursor: pointer;"
                                onclick="toggleOptions(event, 'tipoOptions')">Por tipo</label>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <div id="tipoOptions" style="display: none;">
                                @foreach ($config['tiposEventos'] as $key => $value)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="tipo[]"
                                            value="{{ $key }}" id="tipo{{ $key }}"
                                            {{ in_array($key, request()->query('tipo', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tipo{{ $key }}">
                                            {{ $value }}
                                        </label>
                                    </div>
                                @endforeach
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

                    @if (!empty($config['ambitos']))
                        <div class="mb-3">
                            <label class="form-label" style="cursor: pointer;"
                                onclick="toggleOptions(event, 'ambitoOptions')">Por ámbito</label>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <div id="ambitoOptions" style="display: none;">
                                @foreach ($config['ambitos'] as $key => $value)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="ambito[]"
                                            value="{{ $key }}" id="ambito{{ $key }}"
                                            {{ in_array($key, request()->query('ambito', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ambito{{ $key }}">
                                            {{ $value }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (!empty($config['modalidades']))
                        <div class="mb-3">
                            <label class="form-label" style="cursor: pointer;"
                                onclick="toggleOptions(event, 'modalidadOptions')">Por modalidad</label>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <div id="modalidadOptions" style="display: none;">
                                @foreach ($config['modalidades'] as $key => $value)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="modalidad[]"
                                            value="{{ $key }}" id="modalidad{{ $key }}"
                                            {{ in_array($key, request()->query('modalidad', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="modalidad{{ $key }}">
                                            {{ $value }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (!empty($config['comunicaciones']))
                        <div class="mb-3">
                            <label class="form-label" style="cursor: pointer;"
                                onclick="toggleOptions(event, 'comunicacionOptions')">Por comunicación</label>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <div id="comunicacionOptions" style="display: none;">
                                @foreach ($config['comunicaciones'] as $key => $value)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="comunicacion[]"
                                            value="{{ $key }}" id="comunicacion{{ $key }}"
                                            {{ in_array($key, request()->query('comunicacion', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="comunicacion{{ $key }}">
                                            {{ $value }}
                                        </label>
                                    </div>
                                @endforeach
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
