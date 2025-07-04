<!-- filepath: c:\laragon\www\BlogInvestigacion\resources\views\entities\premios\modal.blade.php -->
<div class="modal fade" id="premioModal" tabindex="-1" aria-labelledby="premioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content custom-modal-border">
            <div class="modal-header custom-modal-header">
                <h5 class="modal-title" id="premioModalLabel">{{ isset($row) ? 'Editar' : 'Crear' }} Premio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom-modal-body">
                <form id="premioForm" class="needs-validation" action="{{ route('admin.premios.store') }}"
                    method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="row mb-2">
                        <!-- ID del premio -->
                        <input type="hidden" name="id_premio" id="id_premio">

                        <!-- Proyecto Premio -->
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="proyecto_premio" name="proyecto_premio"
                                    placeholder=" " maxlength="150" required>
                                <label for="proyecto_premio">Proyecto</label>
                                <div class="invalid-feedback">
                                    Por favor, ingrese el nombre del proyecto.
                                </div>
                            </div>
                        </div>

                        <!-- Resumen Premio -->
                        <div class="mb-3">
                            <div class="form-floating">
                                <textarea class="form-control" id="resumen_premio" name="resumen_premio" style="height: 100px;" placeholder=" "
                                    required></textarea>
                                <label for="resumen_premio">Resumen</label>
                                <div class="invalid-feedback">
                                    Por favor, ingrese un resumen.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Organismo Premio -->
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="organismo_premio" name="organismo_premio" required>
                                    <option value="" disabled selected hidden>Seleccionar</option>
                                    @foreach ($config['organismos'] as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <label for="organismo_premio">Organismo</label>
                            </div>
                        </div>

                        <!-- Ámbito Premio -->
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="ambito_premio" name="ambito_premio" required>
                                    <option value="" disabled selected hidden>Seleccionar</option>
                                    @foreach ($config['ambitos'] as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <label for="ambito_premio">Ámbito</label>
                            </div>
                        </div>
                    </div>
                    {{-- </div> --}}

                    <!-- Autores -->
                    <div id="authorFields_premio">
                        <div class="mb-3 author-field" id="author1">
                            <label class="form-label">Autor 1</label>
                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="nombre_autores[]"
                                            placeholder="Nombre" required>
                                        <label>Nombre(s)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="apellido_autores[]"
                                            placeholder="Apellido" required>
                                        <label>Apellido(s)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón agregar y eliminar autores -->
                    <div class="mb-3">
                        <button type="button" class="btn custom-button-subir" id="addAuthor_premio">
                            <i class="bi bi-plus-circle"></i>
                            Agregar
                        </button>
                        <button type="button" class="btn custom-button-eliminar" id="removeAuthor_premio"
                            style="display: none;">
                            <i class="bi bi-dash-circle"></i>
                            Eliminar
                        </button>
                    </div>

                    <!-- Certificado Premio -->
                    <div class="mb-3">
                        <label for="url_certificado_premio" class="form-label">Certificado</label>
                        <div class="file-input-container">
                            <label class="custom-file-label custom-button-subir">
                                <i class="fa-solid fa-award"></i>
                                <input type="file" id="url_certificado_premio" name="url_certificado_premio"
                                    accept=".pdf">
                            </label>
                            <div class="file-name" id="file-certificado_premio">
                                <span>No se ha elegido un certificado</span>
                            </div>
                        </div>
                    </div>

                    <!-- Imagen Premio -->
                    <div class="mb-3">
                        <label for="url_imagen_premio" class="form-label">Imagen representativa</label>
                        <div class="file-input-container">
                            <label class="custom-file-label custom-button-subir">
                                <i class="fa-solid fa-image"></i>
                                <input type="file" id="url_imagen_premio" name="url_imagen_premio"
                                    accept=".png, .jpg, .jpeg, .webp">
                            </label>
                            <div class="file-name" id="file-imagen_premio">
                                <span>No se ha elegido una imagen</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button id="btn_premio" type="submit" class="btn custom-button-subir"
                            style="float: right;">
                            <i class="{{ isset($row) ? 'bi bi-pencil-square' : 'bi bi-upload' }}"></i>
                            {{ isset($row) ? 'Actualizar' : 'Crear' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
