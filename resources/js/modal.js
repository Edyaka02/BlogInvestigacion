export function inicializarModalAutores(button, authorFieldsId, addAuthorButtonId, removeAuthorButtonId) {
    var nombresAutores = button.getAttribute('data-nombres-autores') ? button.getAttribute('data-nombres-autores').split(',') : [];
    var apellidosAutores = button.getAttribute('data-apellidos-autores') ? button.getAttribute('data-apellidos-autores').split(',') : [];
    var ordenAutores = button.getAttribute('data-orden-autores') ? button.getAttribute('data-orden-autores').split(',').map(Number) : [];

    const max_autores = 5; // Máximo número de autores permitidos
    let cant_autores = nombresAutores.length; // Contador inicial de autores

    const authorFields = document.getElementById(authorFieldsId);
    const agregar_boton_autor = document.getElementById(addAuthorButtonId);
    const remover_boton_autor = document.getElementById(removeAuthorButtonId);

    // Limpiar autores existentes y agregar los actuales en el orden correcto
    authorFields.innerHTML = '';

    // Crear un array de objetos con nombre, apellido y orden
    let autores = nombresAutores.map((nombre, index) => ({
        nombre: nombre.trim(),
        apellido: apellidosAutores[index].trim(),
        orden: ordenAutores[index]
    }));

    // Ordenar los autores por el orden especificado
    autores.sort((a, b) => a.orden - b.orden);

    // Si no hay autores, agregar un campo vacío para el primer autor
    if (autores.length === 0) {
        autores.push({ nombre: '', apellido: '', orden: 1 });
        cant_autores = 1;
    }

    // Agregar los autores ordenados al modal
    autores.forEach(function (autor, index) {
        const nuevo_autor = document.createElement('div');
        nuevo_autor.classList.add('mb-3', 'author-field');
        nuevo_autor.id = `author${index + 1}`;
        nuevo_autor.innerHTML = `
            <label class="form-label">Autor ${index + 1}</label>
            <div class="row mb-2">
                <div class="col-md-6 mb-2">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="nombre_autores[]" placeholder="Nombre" value="${autor.nombre}" required>
                        <label>Nombre(s)</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="apellido_autores[]" placeholder="Apellido" value="${autor.apellido}" required>
                        <label>Apellido(s)</label>
                    </div>
                </div>
            </div>
        `;
        authorFields.appendChild(nuevo_autor);
    });

    // Mostrar el botón de eliminar si hay más de un autor
    remover_boton_autor.style.display = cant_autores > 1 ? 'inline-block' : 'none';

    // Ocultar el botón de agregar si se alcanza el máximo de autores
    agregar_boton_autor.style.display = cant_autores >= max_autores ? 'none' : 'inline-block';


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

            // Ocultar el botón de agregar si se alcanza el máximo de autores
            agregar_boton_autor.style.display = cant_autores >= max_autores ? 'none' : 'inline-block';
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

            // Mostrar el botón de agregar si hay menos del máximo de autores
            agregar_boton_autor.style.display = cant_autores < max_autores ? 'inline-block' : 'none';
        }
    }

    // Asignar eventos a los botones
    agregar_boton_autor.onclick = agregarAutor;
    remover_boton_autor.onclick = removerAutor;
}

export function updateFileName(inputId, displayId, noFileMessage = 'No se ha elegido un archivo') {
    const fileInput = document.getElementById(inputId);
    const fileNameDisplay = document.getElementById(displayId);

    fileInput.addEventListener('change', function () {
        const fileName = fileInput.files[0] ? fileInput.files[0].name : noFileMessage;
        fileNameDisplay.innerHTML = `<span>${fileName}</span>`;
    });
}

export function updateFileDisplay(fileInputId, fileUrl, defaultMessage) {
    const fileInput = document.getElementById(fileInputId);
    if (fileInput) {
        fileInput.innerHTML = fileUrl ? `<span>${fileUrl.split('/').pop()}</span>` : `<span>${defaultMessage}</span>`;
    }
}

export function setupDeleteModal(modalId, formId) {
    const modal = document.getElementById(modalId);
    const form = document.getElementById(formId);

    if (!modal || !form ) return;

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const itemId = button.getAttribute('data-id');
        const itemType = button.getAttribute('data-type'); 
        const itemRoute = button.getAttribute('data-route');

        let actionUrl = `/admin/${itemRoute}/${itemId}`;

        document.getElementById('modalEliminarLabel').textContent = 'Confirmar Eliminación de ' + itemType;
        document.getElementById('modalEliminarBody').textContent = '¿Estás seguro de que quieres eliminar este ' + itemType + '?';

        form.action = actionUrl;
    });
}

export function setModalData(modal, data) {
    for (const [key, value] of Object.entries(data)) {
        const input = modal.querySelector(`#${key}`);
        if (input) {
            input.value = value;
        }
    }
}

export function configureFormForEdit(form, id, entityType) {
    if (id) {
        form.action = `/admin/${entityType}/${id}`;
        form.insertAdjacentHTML('beforeend', '<input type="hidden" name="_method" value="PUT">');
    }
}