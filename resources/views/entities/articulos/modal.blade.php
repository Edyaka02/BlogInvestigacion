<div class="modal fade" id="articulosModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
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
                <form id="articulosForm" action="{{ route('admin.articulos.store') }}" method="POST"
                    enctype="multipart/form-data" novalidate>
                    @csrf
                    <input type="hidden" name="id_articulos" id="id_articulos">

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
                                <label for="titulo_articulos" class="form-label">Título *
                                </label>
                                <input type="text" class="form-control form-control-lg" id="titulo_articulos"
                                    name="titulo_articulos" placeholder="Seguridad de Bases de Datos en Oracle Cloud"
                                    required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="row g-3">
                                <!-- ISSN -->
                                <div class="col-md-4">
                                    <label for="issn_articulos" class="form-label">ISSN *
                                    </label>
                                    <input type="text" class="form-control" id="issn_articulos" name="issn_articulos"
                                        placeholder="1234-5678" pattern="^\d{4}-\d{4}$" maxlength="9" required>
                                    <div class="invalid-feedback">
                                    </div>
                                </div>

                                <!-- Fecha -->
                                <div class="col-md-4">
                                    <label for="fecha_articulos" class="form-label">Fecha *
                                    </label>
                                    <input type="date" class="form-control" id="fecha_articulos" name="fecha_articulos"
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
                                <label for="resumen_articulos" class="form-label fw-medium">
                                    <i class="fa-solid fa-file-text me-1"></i>Resumen *
                                </label>
                                <textarea class="form-control" id="resumen_articulos" name="resumen_articulos" style="height: 120px;"
                                    placeholder="Describe brevemente el contenido, metodología y principales hallazgos..." required></textarea>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Revista -->
                            <div class="mb-3">
                                <label for="revista_articulos" class="form-label fw-medium">
                                    <i class="fa-solid fa-book me-1"></i>Revista *
                                </label>
                                <input type="text" class="form-control" id="revista_articulos" name="revista_articulos"
                                    placeholder="CloudSec Monthly, IEEE Computer Society..." required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- URL Revista -->
                            <div class="mb-3">
                                <label for="url_revista_articulos" class="form-label fw-medium">
                                    <i class="fa-solid fa-link me-1"></i>URL Revista
                                    <span class="badge bg-secondary ms-1">Opcional</span>
                                </label>
                                <input type="url" class="form-control" id="url_revista_articulos"
                                    name="url_revista_articulos" placeholder="https://revista-ejemplo.com">
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
                                        id="addAuthor_articulos">
                                        <i class="fa-solid fa-plus"></i>
                                        <span class="btn-text">Agregar</span>
                                    </button>
                                    <button type="button" class="btn custom-button custom-button-eliminar"
                                        id="removeAuthor_articulos" style="display: none;">
                                        <i class="fa-solid fa-minus"></i>
                                        <span class="btn-text">Eliminar</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="authorFields_articulos" class="authors-container">
                                <!-- Autores se agregarán aquí dinámicamente -->
                            </div>
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

                                        <label for="url_articulos" class="btn custom-button custom-button-rojo w-100 mb-2">
                                            <i class="fa-solid fa-upload me-2"></i>Seleccionar PDF
                                        </label>
                                        <input type="file" id="url_articulos" name="url_articulos" accept=".pdf"
                                            hidden>

                                        <div class="file-preview" id="file-articulos">
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

                                        <label for="url_imagen_articulos" class="btn custom-button custom-button-verde w-100 mb-2">
                                            <i class="fa-solid fa-upload me-2"></i>Seleccionar Imagen
                                        </label>
                                        <input type="file" id="url_imagen_articulos" name="url_imagen_articulos"
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
                <button type="button" class="btn custom-button custom-button-gris" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                    Cancelar
                </button>
                <button id="btn_modal" type="button" form="articulosForm"
                    class="btn custom-button custom-button-subir" style="float: right;">
                    <i id="btn_modal_icon" class="fa-solid fa-upload"></i>
                    <span id="btn_modal_text">Crear</span>
                </button>
            </div>
        </div>
    </div>
</div>
