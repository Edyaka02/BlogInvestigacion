<div class="modal fade" id="articuloModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content custom-modal-border">
            <div class="modal-header custom-modal-header">
                <h5 class="modal-title d-flex align-items-center" id="modalLabel">
                    <i class="fa-solid fa-file-text me-2 text-primary"></i>
                    <span id="modal-action-text">Crear Artículo</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            
            <!-- ✅ MEJOR: Organizar en acordeón para reducir scroll -->
            <div class="modal-body custom-modal-body">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <form id="articuloForm" action="{{ route('admin.articulos.store') }}" method="POST"
                    enctype="multipart/form-data" novalidate>
                    @csrf
                    <input type="hidden" name="id_articulo" id="id_articulo">

                    <!-- ✅ SECCIÓN 1: Información Básica -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0 d-flex align-items-center">
                                <i class="fa-solid fa-info-circle me-2"></i>
                                Información Básica
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Titulo -->
                            <div class="mb-3">
                                <label for="titulo_articulo" class="form-label">Título *
                                </label>
                                <input type="text" class="form-control form-control-lg" id="titulo_articulo"
                                    name="titulo_articulo" placeholder="Seguridad de Bases de Datos en Oracle Cloud"
                                    required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="row g-3">
                                <!-- ISSN -->
                                <div class="col-md-4">
                                    <label for="issn_articulo" class="form-label">ISSN *
                                    </label>
                                    <input type="text" class="form-control" id="issn_articulo" name="issn_articulo"
                                        placeholder="1234-5678" pattern="^\d{4}-\d{4}$" maxlength="9" required>
                                    <div class="invalid-feedback">
                                    </div>
                                </div>

                                <!-- Fecha -->
                                <div class="col-md-4">
                                    <label for="fecha_articulo" class="form-label">Fecha *
                                    </label>
                                    <input type="date" class="form-control" id="fecha_articulo" name="fecha_articulo"
                                        required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Tipo -->
                                <div class="col-md-4">
                                    <label for="id_tipo" class="form-label">Tipo *
                                    </label>
                                    <select class="form-select" id="id_tipo" name="id_tipo" required>
                                        <option value="" disabled selected>Seleccionar tipo...</option>
                                        @foreach ($tipos as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ✅ SECCIÓN 2: Contenido -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0 d-flex align-items-center">
                                <i class="fa-solid fa-align-left me-2"></i>
                                Contenido del Artículo
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Resumen con contador -->
                            <div class="mb-3">
                                <label for="resumen_articulo" class="form-label fw-medium">
                                    <i class="fa-solid fa-file-text me-1"></i>Resumen *
                                </label>
                                <textarea class="form-control" id="resumen_articulo" name="resumen_articulo" style="height: 120px;"
                                    placeholder="Describe brevemente el contenido, metodología y principales hallazgos..." required></textarea>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="form-text text-muted">Mínimo 50 caracteres recomendado</small>
                                    <small class="text-muted" id="resumen-counter">0 caracteres</small>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Revista -->
                            <div class="mb-3">
                                <label for="revista_articulo" class="form-label fw-medium">
                                    <i class="fa-solid fa-book me-1"></i>Revista *
                                </label>
                                <input type="text" class="form-control" id="revista_articulo" name="revista_articulo"
                                    placeholder="CloudSec Monthly, IEEE Computer Society..." required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- URL Revista -->
                            <div class="mb-3">
                                <label for="url_revista_articulo" class="form-label fw-medium">
                                    <i class="fa-solid fa-link me-1"></i>URL Revista
                                    <span class="badge bg-secondary ms-1">Opcional</span>
                                </label>
                                <input type="url" class="form-control" id="url_revista_articulo"
                                    name="url_revista_articulo" placeholder="https://revista-ejemplo.com">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <!-- ✅ SECCIÓN 3: Autores mejorada -->
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 d-flex align-items-center">
                                <i class="fa-solid fa-users me-2"></i>
                                Autores del Artículo
                            </h6>
                            <div class="custom-button-group">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn custom-button custom-button-subir"
                                        id="addAuthor_articulo">
                                        <i class="fa-solid fa-plus"></i>
                                        <span class="btn-text">Agregar</span>
                                    </button>
                                    <button type="button" class="btn custom-button custom-button-eliminar"
                                        id="removeAuthor_articulo" style="display: none;">
                                        <i class="fa-solid fa-minus"></i>
                                        <span class="btn-text">Eliminar</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="authorFields_articulo" class="authors-container">
                                <!-- Autores se agregarán aquí dinámicamente -->
                            </div>
                            {{-- <div class="alert alert-info" id="no-authors-alert">
                                <i class="fa-solid fa-info-circle me-2"></i>
                                No hay autores agregados. Haz clic en "Agregar" para comenzar.
                            </div> --}}
                        </div>
                    </div>

                    <!-- ✅ SECCIÓN 4: Archivos mejorada -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0 d-flex align-items-center">
                                <i class="fa-solid fa-paperclip me-2"></i>
                                Archivos del Artículo
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <!-- Artículo PDF -->
                                <div class="col-md-6">
                                    <div class="upload-card text-center p-4 border rounded">
                                        <div class="mb-3">
                                            <i class="fa-solid fa-file-pdf fa-3x mb-2" style="color: var(--btn-rojo)"></i>
                                            <h6 class="fw-medium">Artículo Científico</h6>
                                            <small class="text-muted">Archivo PDF del artículo</small>
                                        </div>

                                        <label for="url_articulo" class="btn custom-button custom-button-rojo w-100 mb-2">
                                            <i class="fa-solid fa-upload me-2"></i>Seleccionar PDF
                                        </label>
                                        <input type="file" id="url_articulo" name="url_articulo" accept=".pdf"
                                            hidden>

                                        <div class="file-preview" id="file-articulo">
                                            <small class="text-muted">No se ha seleccionado archivo</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Imagen -->
                                <div class="col-md-6">
                                    <div class="upload-card text-center p-4 border rounded">
                                        <div class="mb-3">
                                            <i class="fa-solid fa-image fa-3x mb-2" style="color: var(--btn-verde)"></i>
                                            <h6 class="fw-medium">Imagen Representativa</h6>
                                            <small class="text-muted">Imagen relacionada (opcional)</small>
                                        </div>

                                        <label for="url_imagen_articulo" class="btn custom-button custom-button-verde w-100 mb-2">
                                            <i class="fa-solid fa-upload me-2"></i>Seleccionar Imagen
                                        </label>
                                        <input type="file" id="url_imagen_articulo" name="url_imagen_articulo"
                                            accept=".png,.jpg,.jpeg,.webp" hidden>

                                        <div class="file-preview" id="file-imagen">
                                            <small class="text-muted">No se ha seleccionado archivo</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn custom-button custom-button-cancelar" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                    Cancelar
                </button>
                <button id="btn_modal" type="button" form="articuloForm"
                    class="btn custom-button custom-button-subir" style="float: right;">
                    <i id="btn_modal_icon" class="fa-solid fa-upload"></i>
                    <span id="btn_modal_text">Crear</span>
                </button>
            </div>
        </div>
    </div>
</div>
