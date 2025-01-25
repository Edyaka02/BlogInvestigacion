<form method="GET" action="{{ route('admin.articulos.index') }}" class="mb-4">
    <div class="row">
        <div class="col-md-9">
            <div class="input-group mb-3">
                <input type="text" name="search" class="form-control" placeholder="Buscar artículos..."
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
                        <label class="form-label">Filtrar por tipo de revista</label>
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
                    @if (!empty($years))
                        <div class="mb-3">
                            <label class="form-label">Filtrar por año</label>
                            @foreach ($years as $year)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="anio[]"
                                        value="{{ $year }}" id="anio{{ $year }}"
                                        {{ in_array($year, request()->query('anio', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="anio{{ $year }}">
                                        {{ $year }}
                                    </label>
                                </div>
                            @endforeach
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="intervaloAniosCheckbox">
                                <label class="form-check-label" for="intervaloAniosCheckbox">
                                    Intervalo de años
                                </label>
                            </div>
                            <div id="intervaloAnios" class="mt-2" style="display: none;">
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
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Ordenar por</label>
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
                            <input class="form-check-input" type="radio" name="ordenar" value="fecha_asc"
                                id="ordenarFechaAsc"
                                {{ request()->query('ordenar') == 'fecha_asc' ? 'checked' : '' }}>
                            <label class="form-check-label" for="ordenarFechaAsc">
                                Fecha (Ascendente)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ordenar" value="fecha_desc"
                                id="ordenarFechaDesc"
                                {{ request()->query('ordenar') == 'fecha_desc' ? 'checked' : '' }}>
                            <label class="form-check-label" for="ordenarFechaDesc">
                                Fecha (Descendente)
                            </label>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>