// ✅ CREAR: resources/js/shared/utils/validationHandler.js
import { showValidationErrorsToast } from '../components/toast.js';

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
        if (errorCount > 0) {
            showValidationErrorsToast(errors, {
                message: `Se encontraron ${errorCount} errores. Revisa los campos marcados.`,
                ...options
            });
        }

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