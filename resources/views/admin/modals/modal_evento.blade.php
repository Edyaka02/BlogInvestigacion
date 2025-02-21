<div class="modal fade" id="eventoModal" tabindex="-1" aria-labelledby="eventoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <h5 class="modal-title" id="eventoModalLabel">{{ isset($row) ? 'Editar' : 'Crear' }} Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="eventoForm" action="{{ route('admin.eventos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="POST">
                    <input type="hidden" name="id_evento" id="id_evento">

                    <!-- Título -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="titulo_evento" name="titulo_evento" placeholder=" " required>
                        <label for="titulo_evento">Título</label>
                    </div>

                    <!-- Resumen -->
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="resumen_evento" name="resumen_evento" placeholder=" " style="height: 100px;" required></textarea>
                        <label for="resumen_evento">Resumen</label>
                    </div>

                    <!-- Nombre del Evento -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nombre_evento" name="nombre_evento" placeholder=" " required>
                        <label for="nombre_evento">Nombre del Evento</label>
                    </div>

                    <div class="row">
                        <!-- Tipo de Evento -->
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="tipo_evento" name="tipo_evento" required>
                                    <option value="" disabled selected hidden>Seleccionar</option>
                                    @foreach ($config['tiposEventos'] as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <label for="tipo_evento" class="form-label">Tipo de Evento</label>
                            </div>
                        </div>

                        <!-- Modalidad del Evento -->
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="modalidad_evento" name="modalidad_evento" required>
                                    <option value="" disabled selected hidden>Seleccionar</option>
                                    @foreach ($config['modalidades'] as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <label for="modalidad_evento" class="form-label">Modalidad</label>
                            </div>
                        </div>

                        <!-- Comunicación del Evento -->
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="comunicacion_evento" name="comunicacion_evento" required>
                                    <option value="" disabled selected hidden>Seleccionar</option>
                                    @foreach ($config['comunicacion'] as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                                </select>
                                <label for="comunicacion_evento" class="form-label">Comunicación</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Ámbito del Evento -->
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="ambito_evento" name="ambito_evento" required>
                                    <option value="" disabled selected hidden>Seleccionar</option>
                                    @foreach ($config['ambitos'] as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <label for="ambito_evento" class="form-label">Ámbito</label>
                            </div>
                        </div>

                        <!-- Eje Temático del Evento -->
                        <div class="col-md-8 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="eje_tematico_evento" name="eje_tematico_evento" placeholder=" " required>
                                <label for="eje_tematico_evento">Eje Temático del Evento</label>
                            </div>
                        </div>
                    </div>

                    <!-- Autores -->
                    <div id="authorFields_evento">
                        <div class="mb-3 author-field" id="author1">
                            <label class="form-label">Autor 1</label>
                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="nombre_autores[]" placeholder="Nombre" required>
                                        <label>Nombre(s)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="apellido_autores[]" placeholder="Apellido" required>
                                        <label>Apellido(s)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Agregar y eliminar autores -->
                    <div class="mb-3">
                        <button type="button" class="btn custom-button-subir" id="addAuthor_evento">
                            <i class="bi bi-plus-circle"></i>
                            Agregar
                        </button>
                        <button type="button" class="btn custom-button-eliminar" id="removeAuthor_evento" style="display: none;">
                            <i class="bi bi-dash-circle"></i>
                            Eliminar
                        </button>
                    </div>

                    <!-- URL del Evento -->
                    <div class="form-floating mb-3">
                        <input type="url" class="form-control" id="url_evento" name="url_evento" placeholder=" " required>
                        <label for="url_evento">URL del Evento</label>
                    </div>

                    <!-- Imagen del Evento -->
                    <div class="mb-3">
                        <label for="url_imagen_evento" class="form-label">Imagen representativa</label>
                        <div class="file-input-container">
                            <label class="custom-file-label custom-button-subir">
                                <i class="fa-solid fa-image"></i>
                                <input type="file" id="url_imagen_evento" name="url_imagen_evento" accept=".png, .jpg, .jpeg, .webp" onchange="updateFileName('url_imagen_evento', 'file-imagen-evento')">
                            </label>
                            <div class="file-name" id="file-imagen-evento">
                                <span>No se ha elegido una imagen</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button id="btn_evento" type="submit" class="btn custom-button-subir">
                            <i class="{{ isset($row) ? 'bi bi-pencil-square' : 'bi bi-upload' }}"></i>
                            {{ isset($row) ? 'Actualizar' : 'Crear' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>