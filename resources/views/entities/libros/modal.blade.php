<div class="modal fade" id="libroModal" tabindex="-1" aria-labelledby="libroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content custom-modal-border">
            <div class="modal-header custom-modal-header">
                <h5 class="modal-title" id="libroModalLabel">{{ isset($row) ? 'Editar' : 'Crear' }} Libro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom-modal-body">
                <form id="libroForm" class="needs-validation" action="{{ route('admin.libros.store') }}" method="POST"
                    enctype="multipart/form-data" novalidate>
                    @csrf

                    <!-- ID del libro -->
                    <input type="hidden" name="id_libro" id="id_libro">

                    <!-- Título del Libro -->
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="titulo_libro" name="titulo_libro"
                                placeholder=" " required>
                            <label for="titulo_libro" class="form-label">Título</label>
                        </div>
                    </div>

                    <!-- Capítulo del Libro -->
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="capitulo_libro" name="capitulo_libro"
                                placeholder=" " required>
                            <label for="capitulo_libro" class="form-label">Capítulo</label>
                        </div>
                    </div>

                    <!-- Editorial del Libro -->
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="editorial_libro" name="editorial_libro"
                                placeholder=" " required>
                            <label for="editorial_libro" class="form-label">Editorial</label>
                        </div>
                    </div>


                    <div class="row mb-2">
                        <!-- Año del Libro -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="year_libro" name="year_libro"
                                    min="1000" placeholder=" " required
                                    onkeydown="if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();"
                                    oninput="if(this.value > 9999) this.value = this.value.slice(0, this.value.length - 1);">
                                <label for="year_libro" class="form-label">Año</label>

                            </div>
                        </div>

                        <!-- ISBN -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="isbn_libro" name="isbn_libro"
                                    placeholder=" " pattern="^\d{4}-\d{4}$" maxlength="9" required>
                                <label for="isbn_libro" class="form-label">ISBN</label>
                                <div class="invalid-feedback">
                                    Por favor, ingrese un ISBN válido (formato: 1234-5678).
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Autores -->
                    <div id="authorFields_libro">
                        <div class="mb-3 author-field" id="author1">
                            <label class="form-label">Autor 1</label>
                            <div class="row mb-2">
                                <!-- Nombre -->
                                <div class="col-md-6 mb-2">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="nombre_autores[]"
                                            placeholder="Nombre" required>
                                        <label>Nombre(s)</label>
                                    </div>
                                </div>

                                <!-- Apellido -->
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

                    <!-- Botones para autores-->
                    <div class="mb-3">
                        <!-- Agregar autor -->
                        <button type="button" class="btn custom-button-subir" id="addAuthor_libro">
                            <i class="bi bi-plus-circle"></i>
                            Agregar
                        </button>

                        <!-- Eliminar autor -->
                        <button type="button" class="btn custom-button-eliminar" id="removeAuthor_libro"
                            style="display: none;">
                            <i class="bi bi-dash-circle"></i>
                            Eliminar
                        </button>
                    </div>

                    <!-- DOI del Libro -->
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="doi_libro" name="doi_libro"
                                placeholder=" ">
                            <label for="doi_libro" class="form-label">DOI</label>
                        </div>
                    </div>

                    <!-- Url libro -->
                    <div class="mb-3">
                        <label for="url_libro" class="form-label">Libro o capítulo de libro</label>
                        <div class="file-input-container">
                            <label class="custom-file-label custom-button-subir">
                                <i class="fa-solid fa-file-pdf"></i>
                                <input type="file" id="url_libro" name="url_libro" accept=".pdf">
                            </label>
                            <div class="file-name" id="file-libro">
                                <span>No se ha elegido un libro</span>
                            </div>
                        </div>
                    </div>

                    <!-- Url imagen -->
                    <div class="mb-3">
                        <label for="url_imagen_libro" class="form-label">Imagen representativa</label>
                        <div class="file-input-container">
                            <label class="custom-file-label custom-button-subir">
                                <i class="fa-solid fa-image"></i>
                                <input type="file" id="url_imagen_libro" name="url_imagen_libro"
                                    accept=".png, .jpg, .jpeg, .webp">
                            </label>
                            <div class="file-name" id="file-imagen_libro">
                                <span>No se ha elegido una imagen</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button id="btn_libro" type="submit" class="btn custom-button-subir" style="float: right;">
                            <i class="{{ isset($row) ? 'bi bi-pencil-square' : 'bi bi-upload' }}"></i>
                            {{ isset($row) ? 'Actualizar' : 'Crear' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
