import { ValidationErrorHandler } from '../validations/ValidationErrorHandler.js';

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

        modal.querySelectorAll('.is-valid').forEach(el => {
            el.classList.remove('is-valid');
        });

        // Limpiar mensajes de feedback
        modal.querySelectorAll('.invalid-feedback').forEach(el => {
            el.remove();
        });

        // Limpiar mensajes de feedback válidos
        modal.querySelectorAll('.valid-feedback').forEach(el => {
            el.remove();
        });

        // Limpiar form validation state
        const form = modal.querySelector('form');
        if (form) {
            form.classList.remove('was-validated');
        }

        console.log('✅ Modal limpiado de validaciones con clearValidations de ModalFormCleaner');
    }

    static clearAll(modalId) {
        this.clearValidations(modalId);
        ValidationErrorHandler.clear();

        // ✅ LIMPIAR: También inputs específicos
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.querySelectorAll('input, select, textarea').forEach(input => {
                input.classList.remove('is-invalid', 'is-valid');
            });
        }
    }
}