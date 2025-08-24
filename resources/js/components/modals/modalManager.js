import { ValidationErrorHandler } from '../validations/ValidationErrorHandler.js'
import { ModalFormCleaner } from './ModalFormCleaner.js';

export function initializeModalAuthors(button, authorFieldsId, addAuthorButtonId, removeAuthorButtonId) {
    var authorNames = button.getAttribute('data-nombres-autores') ? button.getAttribute('data-nombres-autores').split(',') : [];
    var authorLastNames = button.getAttribute('data-apellidos-autores') ? button.getAttribute('data-apellidos-autores').split(',') : [];
    var authorOrders = button.getAttribute('data-orden-autores') ? button.getAttribute('data-orden-autores').split(',').map(Number) : [];

    const maxAuthors = 5; // Máximo número de autores permitidos
    let authorCount = authorNames.length; // Contador inicial de autores
    console.log('Autores iniciales:', { authorNames, authorLastNames, authorOrders });

    const authorFields = document.getElementById(authorFieldsId);
    const addAuthorButton = document.getElementById(addAuthorButtonId);
    const removeAuthorButton = document.getElementById(removeAuthorButtonId);

    // Limpiar autores existentes y agregar los actuales en el orden correcto
    authorFields.innerHTML = '';

    // Crear un array de objetos con nombre, apellido y orden
    let authors = authorNames.map((name, index) => ({
        name: name.trim(),
        lastName: authorLastNames[index].trim(),
        order: authorOrders[index]
    }));

    // Ordenar los autores por el orden especificado
    authors.sort((a, b) => a.order - b.order);

    // Si no hay autores, agregar un campo vacío para el primer autor
    if (authors.length === 0) {
        authors.push({ name: '', lastName: '', order: 1 });
        authorCount = 1;
    }

    // Agregar los autores ordenados al modal
    authors.forEach(function (author, index) {
        console.log(`Agregando autor ${index + 1}:`, author);
        const newAuthor = document.createElement('div');
        newAuthor.classList.add('mb-3', 'author-field');
        newAuthor.id = `author${index + 1}`;
        newAuthor.innerHTML = `
            <label class="form-label">Autor ${index + 1}</label>
            <div class="row mb-2">
                <div class="col-md-6 mb-2">
                    <input type="text" class="form-control" name="nombre_autores[]" placeholder="Nombre(s)" value="${author.name}" required>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="apellido_autores[]" placeholder="Apellido(s)" value="${author.lastName}" required>
                    </div>
                </div>
            </div>
        `;
        authorFields.appendChild(newAuthor);
    });

    // Mostrar el botón de eliminar si hay más de un autor
    removeAuthorButton.style.display = authorCount > 1 ? 'inline-block' : 'none';

    // Ocultar el botón de agregar si se alcanza el máximo de autores
    addAuthorButton.style.display = authorCount >= maxAuthors ? 'none' : 'inline-block';


    // Funcionalidad para agregar autores
    function addAuthor() {
        if (authorCount < maxAuthors) {
            authorCount++;
            const newAuthor = document.createElement('div');
            newAuthor.classList.add('mb-3', 'author-field');
            newAuthor.id = `author${authorCount}`;
            newAuthor.innerHTML = `
                <label class="form-label">Autor ${authorCount}</label>
                <div class="row mb-2">
                    <div class="col-md-6 mb-2">
                        <input type="text" class="form-control" name="nombre_autores[]" placeholder="Nombre(s)" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="apellido_autores[]" placeholder="Apellido(s)" required>
                    </div>
                </div>
            `;
            authorFields.appendChild(newAuthor);

            // Mostrar botón de eliminar si hay más de un autor
            removeAuthorButton.style.display = authorCount > 1 ? 'inline-block' : 'none';

            // Ocultar el botón de agregar si se alcanza el máximo de autores
            addAuthorButton.style.display = authorCount >= maxAuthors ? 'none' : 'inline-block';
        }
    }

    // Funcionalidad para remover el último campo de autor
    function removeAuthor() {
        if (authorCount > 1) {
            authorFields.removeChild(authorFields.lastElementChild);
            authorCount--;

            // Ocultar botón de eliminar si solo queda un autor
            if (authorCount === 1) {
                removeAuthorButton.style.display = 'none';
            }

            // Mostrar el botón de agregar si hay menos del máximo de autores
            addAuthorButton.style.display = authorCount < maxAuthors ? 'inline-block' : 'none';
        }
    }

    // Asignar eventos a los botones
    addAuthorButton.onclick = addAuthor;
    removeAuthorButton.onclick = removeAuthor;
}

/**
 * Función para limpiar los autores del modal
 */
export function clearAuthorsModal(fieldName) {
    const authorFields = document.getElementById(fieldName);
    if (authorFields) {
        authorFields.innerHTML = '';
    }
}

/**
 * Función para actualizar la visualización del archivo seleccionado
 */
export function updateFileDisplay(elementId, fileUrl, defaultMessage) {
    const element = document.getElementById(elementId);
    if (element) {
        if (fileUrl && fileUrl.trim() !== '') {
            const fileName = fileUrl.split('/').pop();
            element.innerHTML = `<small class="text-success"><i class="fa-solid fa-check me-1"></i>${fileName}</small>`;
        } else {
            element.innerHTML = `<small class="text-muted">${defaultMessage}</small>`;
        }
    }
}

/** 
 * Función para establecer los datos en el modal
 */
export function setModalData(modal, data) {
    for (const [key, value] of Object.entries(data)) {
        const input = modal.querySelector(`#${key}`);
        if (input) {
            input.value = value;
        }
    }
}

/** 
 * Función para configurar formulario en modo editar 
*/
export function configureFormForEdit(form, id, entityType) {
    if (id) {
        form.action = `/dashboard/${entityType}/${id}`;

        // Verificar si ya existe el input _method antes de agregarlo
        const existingMethod = form.querySelector('input[name="_method"]');
        if (!existingMethod) {
            form.insertAdjacentHTML('beforeend', '<input type="hidden" name="_method" value="PUT">');
        } else {
            existingMethod.value = 'PUT';
        }

        console.log(`Formulario configurado para editar ${entityType} con ID: ${id}`);
    }
}

/** 
 * Función para configurar formulario en modo crear 
*/
export function configureFormForCreate(form, entityType) {
    // Cambiar action del formulario para crear
    const baseUrl = window.location.origin;
    form.action = `${baseUrl}/dashboard/${entityType}`;
    form.method = 'POST';

    // Asegurar que no hay input de método PUT
    const methodInput = form.querySelector('input[name="_method"]');
    if (methodInput) {
        methodInput.remove();
    }

    console.log(`Formulario configurado para crear ${entityType}`);
}

/**
 * Función para actualizar el modal
 */
export function updateModal(isEdit = false, opciones = {}) {
    const config = {
        botonId: 'btn_modal',
        iconoId: 'btn_modal_icon',
        textoId: 'btn_modal_text',
        tituloId: 'modalLabel',
        entidad: 'Elemento',
        ...opciones
    };

    const boton = document.getElementById(config.botonId);
    const icono = document.getElementById(config.iconoId);
    const texto = document.getElementById(config.textoId);
    const titulo = document.getElementById(config.tituloId);

    // Verificar que los elementos existen
    if (!boton || !icono || !texto || !titulo) {
        console.error('No se encontraron los elementos del modal:', {
            boton: !!boton,
            icono: !!icono,
            texto: !!texto,
            titulo: !!titulo
        });
        return;
    }

    if (isEdit) {
        icono.className = 'fa-solid fa-pen-to-square';
        texto.textContent = 'Actualizar';
        titulo.textContent = config.entidad ? `Actualizar ${config.entidad}` : 'Actualizar';
        boton.setAttribute('data-modo', 'editar');
    } else {
        icono.className = 'fa-solid fa-upload';
        texto.textContent = 'Crear';
        titulo.textContent = config.entidad ? `Crear ${config.entidad}` : 'Crear';
        boton.setAttribute('data-modo', 'crear');
    }
}

/**
 * AJAX PARA CREAR/EDITAR
 */
export function setupFormSubmission(formId, modalId, entityType, entityRoute) {
    const form = document.getElementById(formId);
    const modal = document.getElementById(modalId);
    console.log(`Configurando AJAX para formulario: ${formId} en modal: ${modalId} para entidad: ${entityType} y ruta: ${entityRoute}`);

    if (!form || !modal) return;

    form.addEventListener('submit', function (e) {

        ValidationErrorHandler.clear();
        ModalFormCleaner.clearValidations(modalId);

        // ✅ Validación HTML5 nativa
        if (!form.checkValidity()) {
            e.preventDefault();
            form.classList.add('was-validated'); // Bootstrap: muestra los errores
            return; // ⛔ No envía el AJAX si hay errores
        }
        e.preventDefault();

        const formData = new FormData(form);

        const isEdit = form.querySelector('input[name="_method"]')?.value === 'PUT';
        const actionUrl = form.action;

        // AJAX
        fetch(actionUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    console.log(`✅ ${entityType} ${isEdit ? 'actualizado' : 'creado'} exitosamente`);

                    // ✅ LIMPIAR: Modal antes de cerrar
                    ValidationErrorHandler.clear();
                    ModalFormCleaner.clearAll(modalId);

                    form.reset();

                    // Cerrar modal
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) {
                        bsModal.hide();
                    }

                    // Recargar tabla
                    setTimeout(() => {
                        rechargeTable(entityRoute);
                    }, 300);

                } else {
                    if (result.errors) {
                        // ✅ MOSTRAR: Errores del servidor
                        ValidationErrorHandler.show(result.errors);
                        showValidationErrors(result.errors, form);
                    } else {
                        console.error(result.message || 'Error al procesar la solicitud');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // ✅ LIMPIAR: En caso de error
                ValidationErrorHandler.clear();
                ModalFormCleaner.clearAll(modalId);
            })
    });
}

/**
 * Configurar modal de eliminación
 */
export function setupDeleteModal(modalId, formId) {
    const modal = document.getElementById(modalId);
    const form = document.getElementById(formId);

    if (!modal || !form) return;

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const itemId = button.getAttribute('data-id');
        const itemType = button.getAttribute('data-type');
        const itemRoute = button.getAttribute('data-route');
        const itemName = button.getAttribute('data-name') || itemType;

        let actionUrl = `/dashboard/${itemRoute}/${itemId}`;

        document.getElementById('modalEliminarLabel').textContent = `Eliminar ${itemType}`;
        document.getElementById('modalEliminarBody').textContent = `¿Estás seguro de que deseas eliminar ${itemType} "${itemName}"?`;

        form.action = actionUrl;
        form.setAttribute('data-item-type', itemType);
        form.setAttribute('data-item-name', itemName);
        form.setAttribute('data-item-route', itemRoute);
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const actionUrl = form.action;
        const itemType = form.getAttribute('data-item-type');
        const itemRoute = form.getAttribute('data-item-route');

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Eliminando...';
        submitBtn.disabled = true;

        fetch(actionUrl, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    console.log(`✅ ${itemType} eliminado exitosamente`);

                    const bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) {
                        bsModal.hide();
                    }

                    setTimeout(() => {
                        rechargeTable(itemRoute);
                    }, 300);

                } else {
                    console.error(result.message || 'Error al eliminar');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
    });
}

/**
 * Función para recargar tabla según entidad
 */
function rechargeTable(entityRoute) {
    const entityName = entityRoute.charAt(0).toUpperCase() + entityRoute.slice(1);
    const reloadFunctionName = `reloadTable${entityName}`;

    if (typeof window[reloadFunctionName] === 'function') {
        console.log(`🔄 Recargando tabla: ${reloadFunctionName}`);
        window[reloadFunctionName]();
    } else {
        console.log(`⚠️ Función ${reloadFunctionName} no encontrada`);
    }
}

/**
 * Mostrar errores de validación en el formulario
 */
function showValidationErrors(errors, form) {
    // Limpiar errores anteriores
    form.querySelectorAll('.is-invalid').forEach(input => {
        input.classList.remove('is-invalid');
    });
    form.querySelectorAll('.invalid-feedback').forEach(feedback => {
        feedback.remove();
    });

    // Mostrar nuevos errores
    Object.keys(errors).forEach(fieldName => {
        const input = form.querySelector(`[name="${fieldName}"]`) ||
            form.querySelector(`[name="${fieldName}[]"]`);

        if (input) {
            input.classList.add('is-invalid');

            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = errors[fieldName][0];

            input.parentNode.insertBefore(errorDiv, input.nextSibling);
        }
    });
}