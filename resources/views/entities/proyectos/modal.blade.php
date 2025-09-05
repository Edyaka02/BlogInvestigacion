{{-- resources/views/entities/proyectos/modal.blade.php --}}
<div class="modal fade" id="proyectosModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content custom-modal-border">
            <div class="modal-header custom-modal-header">
                <h5 class="modal-title d-flex align-items-center" id="modalLabel">
                    <i class="fa-solid fa-project-diagram me-2 text-primary"></i>
                    <span id="modal-action-text">Crear Proyecto</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            
            <div class="modal-body custom-modal-body">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <form id="proyectosForm" action="{{ route('admin.proyectos.store') }}" method="POST"
                    enctype="multipart/form-data" novalidate>
                    @csrf
                    <input type="hidden" name="id_proyectos" id="id_proyectos">

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
                                <label for="titulo_proyectos" class="form-label">Título del Proyecto *</label>
                                <input type="text" class="form-control form-control-lg" id="titulo_proyectos"
                                    name="titulo_proyectos" placeholder="Desarrollo de Sistema de Gestión Inteligente"
                                    required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="row g-3">
                                <!-- Fecha -->
                                <div class="col-md-6">
                                    <label for="fecha_proyectos" class="form-label">Fecha de Inicio *</label>
                                    <input type="date" class="form-control" id="fecha_proyectos" name="fecha_proyectos"
                                        required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Convocatoria -->
                                <div class="col-md-6">
                                    <label for="convocatoria_proyectos" class="form-label">Convocatoria *</label>
                                    <input type="text" class="form-control" id="convocatoria_proyectos" name="convocatoria_proyectos"
                                        placeholder="FONDECYT 2024, CONICYT..." required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <!-- Organismo -->
                                <div class="col-md-6">
                                    <label for="id_organismo" class="form-label">Organismo Financiador *</label>
                                    <select class="form-select" id="id_organismo" name="id_organismo" required>
                                        <option value="">Seleccionar organismo</option>
                                        @foreach($organismos as $id => $nombre)
                                            <option value="{{ $id }}">{{ $nombre }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Ámbito -->
                                <div class="col-md-6">
                                    <label for="id_ambito" class="form-label">Ámbito de Investigación *</label>
                                    <select class="form-select" id="id_ambito" name="id_ambito" required>
                                        <option value="">Seleccionar ámbito</option>
                                        @foreach($ambitos as $id => $nombre)
                                            <option value="{{ $id }}">{{ $nombre }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ✅ SECCIÓN 2: Descripción del Proyecto -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0 d-flex align-items-center">
                                <i class="fa-solid fa-file-text me-2"></i>
                                Descripción del Proyecto
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Resumen -->
                            <div class="mb-3">
                                <label for="resumen_proyectos" class="form-label fw-medium">
                                    <i class="fa-solid fa-align-left me-1"></i>Resumen/Descripción
                                    <span class="badge bg-secondary ms-1">Opcional</span>
                                </label>
                                <textarea class="form-control" id="resumen_proyectos" name="resumen_proyectos" rows="4"
                                    placeholder="Describe brevemente los objetivos, metodología y resultados esperados del proyecto..."></textarea>
                                <div class="invalid-feedback"></div>
                                <div class="form-text">
                                    <i class="fa-solid fa-lightbulb me-1"></i>
                                    Incluye objetivos, metodología y impacto esperado del proyecto
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ✅ SECCIÓN 3: Autores/Investigadores -->
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 d-flex align-items-center">
                                <i class="fa-solid fa-users me-2"></i>
                                Investigadores del Proyecto
                            </h6>
                            <div class="custom-button-group">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn custom-button custom-button-subir"
                                        id="addAuthor_proyectos">
                                        <i class="fa-solid fa-plus"></i>
                                        <span class="btn-text">Agregar</span>
                                    </button>
                                    <button type="button" class="btn custom-button custom-button-eliminar"
                                        id="removeAuthor_proyectos" style="display: none;">
                                        <i class="fa-solid fa-minus"></i>
                                        <span class="btn-text">Eliminar</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="authorFields_proyectos" class="authors-container">
                                <!-- Autores se agregarán aquí dinámicamente -->
                            </div>
                        </div>
                    </div>

                    <!-- ✅ SECCIÓN 4: Archivos -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0 d-flex align-items-center">
                                <i class="fa-solid fa-paperclip me-2"></i>
                                Archivos del Proyecto
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <!-- Documento del Proyecto -->
                                <div class="col-md-6">
                                    <div class="upload-card text-center p-4 border rounded">
                                        <div class="mb-3">
                                            <i class="fa-solid fa-file-pdf fa-3x mb-2" style="color: var(--btn-rojo)"></i>
                                            <h6 class="fw-medium">Documento del Proyecto</h6>
                                            <small class="text-muted">Propuesta, informe o memoria PDF</small>
                                        </div>

                                        <label for="url_proyectos" class="btn custom-button custom-button-rojo w-100 mb-2">
                                            <i class="fa-solid fa-upload me-2"></i>Seleccionar PDF
                                        </label>
                                        <input type="file" id="url_proyectos" name="url_proyectos" accept=".pdf" hidden>

                                        <div class="file-preview" id="file-proyectos">
                                            <small class="text-muted">No se ha seleccionado archivo</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Imagen del Proyecto -->
                                <div class="col-md-6">
                                    <div class="upload-card text-center p-4 border rounded">
                                        <div class="mb-3">
                                            <i class="fa-solid fa-image fa-3x mb-2" style="color: var(--btn-verde)"></i>
                                            <h6 class="fw-medium">Imagen del Proyecto</h6>
                                            <small class="text-muted">Diagrama, esquema o foto (opcional)</small>
                                        </div>

                                        <label for="url_imagen_proyectos" class="btn custom-button custom-button-verde w-100 mb-2">
                                            <i class="fa-solid fa-upload me-2"></i>Seleccionar Imagen
                                        </label>
                                        <input type="file" id="url_imagen_proyectos" name="url_imagen_proyectos"
                                            accept=".png,.jpg,.jpeg,.webp" hidden>

                                        <div class="file-preview" id="file-imagen-proyectos">
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
                <button id="btn_modal" type="button" form="proyectosForm"
                    class="btn custom-button custom-button-subir" style="float: right;">
                    <i id="btn_modal_icon" class="fa-solid fa-upload"></i>
                    <span id="btn_modal_text">Crear</span>
                </button>
            </div>
        </div>
    </div>
</div>