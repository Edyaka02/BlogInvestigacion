/**
 * Función para manejar la adición y eliminación de campos de autor en un formulario.
 */
function inputs_autor(containerId, addButtonId, removeButtonId) {
    const max_autores = 5; // Máximo número de autores permitidos
    let cant_autores = 1; // Contador inicial de autores

    const agregar_boton_autor = document.getElementById(addButtonId);
    const remover_boton_autor = document.getElementById(removeButtonId);
    const authorFields = document.getElementById(containerId);

    if (agregar_boton_autor) {
        agregar_boton_autor.onclick = function () {
            if (cant_autores < max_autores) {
                cant_autores++;
                const nuevo_autor = document.createElement('div');
                nuevo_autor.classList.add('mb-3', 'author-field');
                nuevo_autor.id = `author${cant_autores}`;
                nuevo_autor.innerHTML = `
                    <label class="form-label">Autor ${cant_autores}</label>
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
                `;
                authorFields.appendChild(nuevo_autor);

                // Mostrar botón de eliminar
                remover_boton_autor.style.display = 'inline-block';
            }
        };
    }

    // Remover el último campo de autor
    if (remover_boton_autor) {
        remover_boton_autor.onclick = function () {
            if (cant_autores > 1) {
                authorFields.removeChild(authorFields.lastElementChild);
                cant_autores--;

                // Ocultar botón de eliminar si hay un solo autor
                if (cant_autores === 1) {
                    this.style.display = 'none';
                }
            }
        };
    }
}

/**
 * Establece el valor del campo de año con el año actual.
 */
function establecer_Year(inputYearId) {
    const yearInput = document.getElementById(inputYearId);
    if (yearInput) {
        // Obtiene el año actual
        const currentYear = new Date().getFullYear();
        // Establece el valor del campo de año con el año actual
        yearInput.value = currentYear;
    }
}

/**
 * Limpia el modal de artículo cuando se cierra.
 */
function limpiar_modal_articulo() {
    const articuloModal = document.getElementById('articuloModal');

    if (articuloModal) {
        // Agrega un evento al cerrar el modal
        articuloModal.addEventListener('hidden.bs.modal', function () {
            const form = articuloModal.querySelector('form');
            // Resetea el formulario
            form.reset();

            // Inputs que se deben limpiar
            const inputsToClear = ['issn', 'titulo', 'resumen', 'revista', 'url_revista', 'url_articulo', 'url_imagen'];

            // RECORRE TODOS LO INPUTS Y LOS LIMPIA
            inputsToClear.forEach(id => {
                const input = form.querySelector(`#${id}`);
                if (input) {
                    input.value = '';
                }
            });

            // Limpia los campos de autores que se agregaron
            const authorFields = document.getElementById('authorFields');
            while (authorFields.children.length > 1) {
                authorFields.removeChild(authorFields.lastElementChild);
            }

            // Oculta el botón de eliminar autores
            document.getElementById('removeAuthor').style.display = 'none';
        });
    }
}

function updateFileName(inputId, fileNameId) {
    var fileInput = document.getElementById(inputId);
    var fileNameElement = document.getElementById(fileNameId);

    if (fileInput.files.length > 0) {
        var fileName = fileInput.files[0].name;
        fileNameElement.innerText = fileName;
    } else {
        fileNameElement.innerText = "No se ha elegido un archivo";
    }
}

// Función para inicializar el modal con datos de autores
function inicializarModalAutores(button, authorFieldsId, addAuthorButtonId, removeAuthorButtonId) {
    var nombresAutores = button.getAttribute('data-nombres-autores') ? button.getAttribute('data-nombres-autores').split(', ') : [];
    var apellidosAutores = button.getAttribute('data-apellidos-autores') ? button.getAttribute('data-apellidos-autores').split(', ') : [];

    const max_autores = 5; // Máximo número de autores permitidos
    let cant_autores = nombresAutores.length; // Contador inicial de autores

    const authorFields = document.getElementById(authorFieldsId);
    const agregar_boton_autor = document.getElementById(addAuthorButtonId);
    const remover_boton_autor = document.getElementById(removeAuthorButtonId);

    // Limpiar autores existentes y agregar los actuales
    authorFields.innerHTML = '';

    nombresAutores.forEach(function (nombre, index) {
        const apellido = apellidosAutores[index];  // Obtener el apellido correspondiente

        const nuevo_autor = document.createElement('div');
        nuevo_autor.classList.add('mb-3', 'author-field');
        nuevo_autor.id = `author${index + 1}`;
        nuevo_autor.innerHTML = `
            <label class="form-label">Autor ${index + 1}</label>
            <div class="row mb-2">
                <div class="col-md-6 mb-2">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="nombre_autores[]" placeholder="Nombre" value="${nombre}" required>
                        <label>Nombre(s)</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="apellido_autores[]" placeholder="Apellido" value="${apellido}" required>
                        <label>Apellido(s)</label>
                    </div>
                </div>
            </div>
        `;
        authorFields.appendChild(nuevo_autor);
    });

    // Mostrar el botón de eliminar si hay más de un autor
    remover_boton_autor.style.display = cant_autores > 1 ? 'inline-block' : 'none';

    // Funcionalidad para agregar autores
    function agregarAutor() {
        if (cant_autores < max_autores) {
            cant_autores++;
            const nuevo_autor = document.createElement('div');
            nuevo_autor.classList.add('mb-3', 'author-field');
            nuevo_autor.id = `author${cant_autores}`;
            nuevo_autor.innerHTML = `
                <label class="form-label">Autor ${cant_autores}</label>
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
            `;
            authorFields.appendChild(nuevo_autor);

            // Mostrar botón de eliminar si hay más de un autor
            remover_boton_autor.style.display = cant_autores > 1 ? 'inline-block' : 'none';
        }
    }

    // Funcionalidad para remover el último campo de autor
    function removerAutor() {
        if (cant_autores > 1) {
            authorFields.removeChild(authorFields.lastElementChild);
            cant_autores--;

            // Ocultar botón de eliminar si solo queda un autor
            if (cant_autores === 1) {
                remover_boton_autor.style.display = 'none';
            }
        }
    }

    // Asignar eventos a los botones
    agregar_boton_autor.onclick = agregarAutor;
    remover_boton_autor.onclick = removerAutor;
}

//Coloca los nombres de los autores
function autores(modal, autor, addAuthor, removeAuthor) {
    document.getElementById(modal).addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        inicializarModalAutores(button, autor, addAuthor, removeAuthor);
    });
}

function llamarEliminar() {
    document.getElementById('modalEliminar').addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        document.getElementById('id_eliminar').value = id;
    });
}

function buscador() {
    let debounceTimeout;
    document.getElementById('id_busqueda').addEventListener('keyup', function () {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            var input = document.getElementById('id_busqueda').value.toLowerCase();
            var items = document.getElementsByClassName('card_busqueda');
            for (var i = 0; i < items.length; i++) {
                var title = items[i].getElementsByClassName('card-title')[0].innerText.toLowerCase();
                var button = items[i].querySelector('.custom-button-editar');
                var author = (button.getAttribute('data-nombres-autores') + ' ' + button.getAttribute('data-apellidos-autores')).toLowerCase();
                var revista = (button.getAttribute('data-revista') || '').toLowerCase();
                var evento = (button.getAttribute('data-nombre') || '').toLowerCase();
                var convocatoria = (button.getAttribute('data-convocatoria') || '').toLowerCase();
                var capitulo = (button.getAttribute('data-capitulo') || '').toLowerCase();
                var editorial = (button.getAttribute('data-editorial') || '').toLowerCase();

                if (title.includes(input) || author.includes(input) || revista.includes(input) || evento.includes(input) || convocatoria.includes(input) || capitulo.includes(input) || editorial.includes(input)) {
                    items[i].style.display = '';
                } else {
                    items[i].style.display = 'none';
                }
            }
        }, 300);  // Espera 300ms antes de ejecutar la búsqueda
    });
}

function ordenarPorParametro() {
    document.getElementById('id_ordenar').addEventListener('change', function() {
        var selectedOption = this.value;

        // Redirigir a la misma página con el parámetro de orden en la URL
        window.location.href = window.location.pathname + '?orden=' + selectedOption;
    });
}

function obtenerNombreArchivo(rutaCompleta) {
    return rutaCompleta.split('/').pop();
}