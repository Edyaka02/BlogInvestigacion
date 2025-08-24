// ✅ CREAR: resources/js/shared/utils/validationHandler.js
// import { showValidationErrorsToast } from '../components/toast.js';

/**
 * Manejo de errores de validación en formularios
 */
export class ValidationErrorHandler {
    static show(errors, options = {}) {
        // ✅ VERIFICAR: Solo mostrar si realmente hay errores del servidor
        if (!errors || Object.keys(errors).length === 0) {
            console.log('No hay errores para mostrar');
            return 0;
        }

        // Limpiar errores anteriores
        this.clear();

        // Mostrar nuevos errores solo si vienen del servidor
        let errorCount = 0;
        Object.keys(errors).forEach(field => {
            const input = document.getElementById(field) || document.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');

                // const feedback = document.createElement('div');
                // feedback.className = 'invalid-feedback';
                // feedback.textContent = errors[field][0];
                // input.parentNode.appendChild(feedback);

                errorCount++;
            }
        });

        // ✅ SOLO mostrar toast si hay errores reales
        // if (errorCount > 0) {
        //     showValidationErrorsToast(errors, {
        //         message: `Se encontraron ${errorCount} errores. Revisa los campos marcados.`,
        //         ...options
        //     });
        // }

        return errorCount;
    }

    static clear() {
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(el => {
            el.remove();
        });

        // ✅ Limpiar también mensajes de texto rojo
        document.querySelectorAll('.text-danger').forEach(el => {
            if (el.textContent.includes('field is required')) {
                el.textContent = '';
                el.style.display = 'none';
            }
        });
    }
}

/**
 * Limpiador específico para modales
 */
export class ModalFormCleaner {
    static clearValidations(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        // Limpiar clases de Bootstrap
        modal.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });

        // Limpiar mensajes de feedback
        modal.querySelectorAll('.invalid-feedback').forEach(el => {
            el.remove();
        });

        // Limpiar mensajes de texto rojo específicos
        // const errorMessages = [
        //     // 'The titulo articulo field is required.',
        //     'The issn articulo field is required.',
        //     'The titulo articulo field is required.',
        //     'The fecha articulo field is required.',
        //     'The id tipo field is required.',
        //     'The resumen articulo field is required.',
        //     'The revista articulo field is required.',
        //     'The url revista articulo field is required.'
        // ];

        // modal.querySelectorAll('*').forEach(element => {
        //     if (element.textContent && errorMessages.includes(element.textContent.trim())) {
        //         element.textContent = '';
        //         element.style.display = 'none';
        //     }
        // });

        // Limpiar form validation state
        const form = modal.querySelector('form');
        if (form) {
            form.classList.remove('was-validated');
        }

        console.log('✅ Modal limpiado de validaciones');
    }

    static clearAll(modalId) {
        this.clearValidations(modalId);
        ValidationErrorHandler.clear();
    }
}


// /**
//  * 1. MANEJO PURO DE VALIDACIONES (sin conocer de modales)
//  */
// export class ValidationErrorHandler {
//     static show(errors, formElement = document) {
//         if (!errors || Object.keys(errors).length === 0) return 0;

//         this.clear(formElement);
//         let errorCount = 0;

//         Object.keys(errors).forEach(fieldName => {
//             const input = formElement.querySelector(`[name="${fieldName}"]`) ||
//                 formElement.querySelector(`[name="${fieldName}[]"]`) ||
//                 formElement.querySelector(`#${fieldName}`);

//             if (input) {
//                 input.classList.add('is-invalid');

//                 const errorDiv = document.createElement('div');
//                 errorDiv.className = 'invalid-feedback';
//                 errorDiv.textContent = errors[fieldName][0];

//                 // ✅ INSERTAR: Después del input o del contenedor padre
//                 const insertAfter = input.parentNode.querySelector('.input-group') || input;
//                 insertAfter.parentNode.insertBefore(errorDiv, insertAfter.nextSibling);

//                 errorCount++;
//             }
//         });

//         return errorCount;
//     }

//     static clear(container = document) {
//         // ✅ LIMPIAR: Solo elementos de validación
//         container.querySelectorAll('.is-invalid').forEach(el => {
//             el.classList.remove('is-invalid');
//         });

//         container.querySelectorAll('.invalid-feedback').forEach(el => {
//             el.remove();
//         });

//         // ✅ LIMPIAR: Estado de validación de formularios
//         container.querySelectorAll('form.was-validated').forEach(form => {
//             form.classList.remove('was-validated');
//         });
//     }

//     static hasErrors(container = document) {
//         return container.querySelectorAll('.is-invalid').length > 0;
//     }

//     static countErrors(container = document) {
//         return container.querySelectorAll('.is-invalid').length;
//     }
// }

// /**
//  * 2. LIMPIEZA ESPECÍFICA DE FORMULARIOS (sin conocer de validaciones)
//  */
// export class FormCleaner {
//     static resetForm(formId) {
//         const form = document.getElementById(formId);
//         if (!form) return false;

//         // ✅ RESET: Formulario completo
//         form.reset();

//         // ✅ LIMPIAR: Campos específicos que no se resetean automáticamente
//         const specialFields = form.querySelectorAll('input[type="hidden"][name="_method"]');
//         specialFields.forEach(field => field.remove());

//         // ✅ LIMPIAR: Estados especiales
//         form.classList.remove('was-validated');

//         return true;
//     }

//     static clearFieldsByName(fieldNames, container = document) {
//         fieldNames.forEach(fieldName => {
//             const field = container.querySelector(`[name="${fieldName}"]`) ||
//                 container.querySelector(`#${fieldName}`);
//             if (field) {
//                 if (field.type === 'checkbox' || field.type === 'radio') {
//                     field.checked = false;
//                 } else if (field.tagName === 'SELECT') {
//                     field.selectedIndex = 0;
//                 } else {
//                     field.value = '';
//                 }
//             }
//         });
//     }

//     static clearFileInputs(container = document) {
//         container.querySelectorAll('input[type="file"]').forEach(input => {
//             input.value = '';
//         });
//     }

//     static clearCustomElements(selectors, container = document) {
//         selectors.forEach(selector => {
//             const elements = container.querySelectorAll(selector);
//             elements.forEach(el => {
//                 el.innerHTML = '';
//                 el.textContent = '';
//             });
//         });
//     }
// }

// /**
//  * 3. ESPECIALIZADO EN MODALES (usa las otras dos clases)
//  */
// export class ModalManager {
//     static clearAll(modalId) {
//         const modal = document.getElementById(modalId);
//         if (!modal) return false;

//         console.log(`🧹 Limpiando modal completo: ${modalId}`);

//         // ✅ LIMPIAR: Validaciones
//         ValidationErrorHandler.clear(modal);

//         // ✅ LIMPIAR: Formularios
//         const forms = modal.querySelectorAll('form');
//         forms.forEach(form => {
//             FormCleaner.resetForm(form.id);
//         });

//         // ✅ LIMPIAR: Archivos específicos
//         FormCleaner.clearFileInputs(modal);

//         // ✅ LIMPIAR: Elementos personalizados del modal
//         FormCleaner.clearCustomElements([
//             '.author-field',
//             '.file-display',
//             '.dynamic-content'
//         ], modal);

//         return true;
//     }

//     static clearValidationsOnly(modalId) {
//         const modal = document.getElementById(modalId);
//         if (!modal) return false;

//         ValidationErrorHandler.clear(modal);
//         return true;
//     }

//     static prepareForEdit(modalId, data = {}) {
//         const modal = document.getElementById(modalId);
//         if (!modal) return false;

//         // ✅ LIMPIAR: Solo validaciones, mantener datos
//         this.clearValidationsOnly(modalId);

//         // ✅ CONFIGURAR: Para edición
//         console.log(`📝 Preparando modal ${modalId} para edición`);
//         return true;
//     }

//     static prepareForCreate(modalId) {
//         const modal = document.getElementById(modalId);
//         if (!modal) return false;

//         // ✅ LIMPIAR: Todo para empezar limpio
//         this.clearAll(modalId);

//         console.log(`➕ Preparando modal ${modalId} para creación`);
//         return true;
//     }
// }