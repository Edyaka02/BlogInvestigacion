{{-- filepath: c:\laragon\www\BlogInvestigacion\resources\views\entities\prototipos\modal.blade.php --}}
{{-- resources/views/entities/prototipos/modal.blade.php --}}
<div class="modal fade" id="prototiposModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content custom-modal-border">
            <div class="modal-header custom-modal-header">
                <h5 class="modal-title d-flex align-items-center" id="modalLabel">
                    <i class="fa-solid fa-flask me-2 text-primary"></i>
                    <span id="modal-action-text">Crear Prototipo</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            
            <div class="modal-body custom-modal-body">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <form id="prototiposForm" action="{{ route('admin.prototipos.store') }}" method="POST"
                    enctype="multipart/form-data" novalidate>
                    @csrf
                    <input type="hidden" name="id_prototipo" id="id_prototipo">

                    <!-- ✅ SECCIÓN 1: Información Básica -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0 d-flex align-items-center">
                                <i class="fa-solid fa-info-circle me-2"></i>
                                Información Básica
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Nombre del Prototipo -->
                            <div class="mb-3">
                                <label for="nombre_prototipo" class="form-label">Nombre del Prototipo *</label>
                                <input type="text" class="form-control form-control-lg" id="nombre_prototipo"
                                    name="nombre_prototipo" placeholder="Sistema de Monitoreo Ambiental IoT"
                                    required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="row g-3">
                                <!-- Propósito -->
                                <div class="col-md-4">
                                    <label for="proposito_prototipo" class="form-label">Propósito *</label>
                                    <input type="text" class="form-control" id="proposito_prototipo" name="proposito_prototipo"
                                        placeholder="Investigación, Educativo, Industrial..." required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Fecha -->
                                <div class="col-md-4">
                                    <label for="fecha_prototipo" class="form-label">Fecha *</label>
                                    <input type="date" class="form-control" id="fecha_prototipo" name="fecha_prototipo"
                                        required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Institución -->
                                <div class="col-md-4">
                                    <label for="institucion_prototipo" class="form-label">Institución *</label>
                                    <input type="text" class="form-control" id="institucion_prototipo" name="institucion_prototipo"
                                        placeholder="Universidad, Centro de Investigación..." required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ✅ SECCIÓN 2: Descripción Detallada -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0 d-flex align-items-center">
                                <i class="fa-solid fa-file-text me-2"></i>
                                Descripción Detallada
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Descripción General -->
                            <div class="mb-3">
                                <label for="descripcion_prototipo" class="form-label">Descripción General *</label>
                                <textarea class="form-control" id="descripcion_prototipo" name="descripcion_prototipo" 
                                    rows="4" placeholder="Describe el prototipo, su funcionamiento y características principales..." required></textarea>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="row g-3">
                                <!-- Objetivo -->
                                <div class="col-md-6">
                                    <label for="objetivo_prototipo" class="form-label">Objetivo *</label>
                                    <textarea class="form-control" id="objetivo_prototipo" name="objetivo_prototipo" 
                                        rows="3" placeholder="¿Cuál es el objetivo principal del prototipo?" required></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Características -->
                                <div class="col-md-6">
                                    <label for="caracteristicas_prototipo" class="form-label">Características *</label>
                                    <textarea class="form-control" id="caracteristicas_prototipo" name="caracteristicas_prototipo" 
                                        rows="3" placeholder="Lista las características técnicas principales..." required></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ✅ SECCIÓN 3: Autores -->
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 d-flex align-items-center">
                                <i class="fa-solid fa-users me-2"></i>
                                Autores del Prototipo
                            </h6>
                            <div class="custom-button-group">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn custom-button custom-button-subir"
                                        id="addAuthor_prototipos">
                                        <i class="fa-solid fa-plus"></i>
                                        <span class="btn-text">Agregar</span>
                                    </button>
                                    <button type="button" class="btn custom-button custom-button-eliminar"
                                        id="removeAuthor_prototipos" style="display: none;">
                                        <i class="fa-solid fa-minus"></i>
                                        <span class="btn-text">Eliminar</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="authorFields_prototipos" class="authors-container">
                                <!-- Autores se agregarán aquí dinámicamente -->
                            </div>
                        </div>
                    </div>

                    <!-- ✅ SECCIÓN 4: Archivos -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0 d-flex align-items-center">
                                <i class="fa-solid fa-paperclip me-2"></i>
                                Archivos del Prototipo
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <!-- Documento del Prototipo -->
                                <div class="col-md-6">
                                    <div class="upload-card text-center p-4 border rounded">
                                        <div class="mb-3">
                                            <i class="fa-solid fa-file-pdf fa-3x mb-2" style="color: var(--btn-rojo)"></i>
                                            <h6 class="fw-medium">Documento del Prototipo</h6>
                                            <small class="text-muted">Archivo PDF del prototipo</small>
                                        </div>

                                        <label for="url_prototipo" class="btn custom-button custom-button-rojo w-100 mb-2">
                                            <i class="fa-solid fa-upload me-2"></i>Seleccionar PDF
                                        </label>
                                        <input type="file" id="url_prototipo" name="url_prototipo" accept=".pdf,.doc,.docx" hidden>

                                        <div class="file-preview" id="file-prototipo">
                                            <small class="text-muted">No se ha seleccionado archivo</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Imagen del Prototipo -->
                                <div class="col-md-6">
                                    <div class="upload-card text-center p-4 border rounded">
                                        <div class="mb-3">
                                            <i class="fa-solid fa-image fa-3x mb-2" style="color: var(--btn-verde)"></i>
                                            <h6 class="fw-medium">Imagen del Prototipo</h6>
                                            <small class="text-muted">Imagen representativa (opcional)</small>
                                        </div>

                                        <label for="url_imagen_prototipo" class="btn custom-button custom-button-verde w-100 mb-2">
                                            <i class="fa-solid fa-upload me-2"></i>Seleccionar Imagen
                                        </label>
                                        <input type="file" id="url_imagen_prototipo" name="url_imagen_prototipo"
                                            accept=".png,.jpg,.jpeg,.webp" hidden>

                                        <div class="file-preview" id="file-imagen-prototipo">
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
                <button id="btn_modal" type="button" form="prototiposForm"
                    class="btn custom-button custom-button-subir" style="float: right;">
                    <i id="btn_modal_icon" class="fa-solid fa-upload"></i>
                    <span id="btn_modal_text">Crear</span>
                </button>
            </div>
        </div>
    </div>
</div>