<div class="modal fade" id="articuloModal" tabindex="-1" aria-labelledby="articuloModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <h5 class="modal-title" id="articuloModalLabel">{{ isset($row) ? 'Editar' : 'Crear' }} Artículo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom-modal-body">
                <form id="articuloForm" class="needs-validation"
                    action="{{ isset($row) ? route('admin.articulos.update', $row->ID_ARTICULO) : route('admin.articulos.store') }}"
                    method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    @if (isset($row))
                        @method('PUT')
                    @endif
                    <div class="row mb-2">
                        <!-- Accion -->
                        {{-- <input type="text" name="accion" id="accion" value="{{ $accion['accion'] }}"> --}}

                        <!-- ID del articulo -->
                        <input type="hidden" name="id_articulo" id="id_articulo">


                        <!-- ISSN -->
                        <div class="col-md-4 mb-2">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="issn_articulo" name="issn_articulo"
                                    placeholder=" " required>
                                <label for="issn_articulo">ISSN</label>
                            </div>
                        </div>

                        <!-- Fecha -->
                        <div class="col-md-4 mb-2">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="fecha_articulo" name="fecha_articulo"
                                    required>
                                <label for="fecha_articulo" class="form-label">Fecha</label>
                            </div>
                        </div>

                        <!-- Tipo de articulo -->
                        {{-- <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select" id="tipo_articulo" name="tipo_articulo" required>
                                    <option value="" disabled selected hidden>Seleccionar</option>
                                    <option value="Investigacion">Investigación</option>
                                    <option value="Divulgacion">Divulgación</option>
                                </select>
                                <label for="tipo" class="form-label">Tipo de Artículo</label>
                            </div>
                        </div> --}}
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select" id="tipo_articulo" name="tipo_articulo" required>
                                    <option value="" disabled selected hidden>Seleccionar</option>
                                    @foreach($tiposArticulos as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <label for="tipo" class="form-label">Tipo de Artículo</label>
                            </div>
                        </div>
                    </div>

                    <!-- Titulo -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="titulo_articulo" name="titulo_articulo"
                            placeholder=" " required>
                        <label for="titulo_articulo" class="form-label">Título</label>
                        <div class="invalid-feedback">
                            Por favor, ingrese un ISSN válido.
                        </div>
                    </div>

                    <!-- Resumen -->
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="resumen_articulo" name="resumen_articulo" style="height: 150px;" placeholder=" "
                            required></textarea>
                        <label for="resumen_articulo" class="form-label">Resumen</label>
                    </div>

                    <!-- Revista -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="revista_articulo" name="revista_articulo"
                            placeholder=" " required>
                        <label for="revista_articulo" class="form-label">Revista</label>
                    </div>

                    <!-- Autores -->
                    <div id="authorFields_articulo">
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

                    <!-- Boton agregar y eliminar -->
                    <div class="mb-3">
                        <button type="button" class="btn custom-button-subir" id="addAuthor_articulo">
                            <i class="bi bi-plus-circle"></i>
                            Agregar
                        </button>
                        <button type="button" class="btn custom-button-eliminar" id="removeAuthor_articulo"
                            style="display: none;">
                            <i class="bi bi-dash-circle"></i>
                            Eliminar
                        </button>
                    </div>

                    <!-- URL Revista -->
                    <div class="form-floating mb-3">
                        <input type="url" class="form-control" id="url_revista_articulo"
                            name="url_revista_articulo" placeholder=" ">
                        <label for="url_revista_articulo" class="form-label">URL Revista</label>
                    </div>

                    <!-- Url Articulos -->
                    <div class="mb-3">
                        <label for="url_articulo" class="form-label">Articulo cientifico</label>
                        <div class="file-input-container">
                            <label class="custom-file-label custom-button-subir">
                                <i class="fa-solid fa-file-pdf"></i>
                                <input type="file" id="url_articulo" name="url_articulo" accept=".pdf">
                            </label>
                            <div class="file-name" id="file-articulo">
                                <span>No se ha elegido un articulo</span>
                            </div>
                        </div>
                    </div>

                    <!-- Url imagen -->
                    <div class="mb-3">
                        <label for="url_imagen" class="form-label">Imagen representativa</label>
                        <div class="file-input-container">
                            <label class="custom-file-label custom-button-subir">
                                <i class="fa-solid fa-image"></i>
                                <input type="file" id="url_imagen_articulo" name="url_imagen_articulo"
                                    accept=".png, .jpg, .jpeg, .webp">
                            </label>
                            <div class="file-name" id="file-imagen">
                                <span>No se ha elegido una imagen</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button id="btn_articulo" type="submit" class="btn custom-button-subir"
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
