// ✅ CREAR: resources/js/shared/utils/ajaxFormController.js
import { ButtonLoader } from './buttonLoader.js';
import { TableReloader } from './tableReloader.js';
import { ModalHandler } from './modalHandler.js';
import { PersistentMessage } from './persistentMessage.js';
import { ValidationErrorHandler } from './validationHandler.js';
import { showSuccessToast, showErrorToast } from '../components/toast.js';

/**
 * Controlador de formularios AJAX
 */
export class AjaxFormController {
    constructor(formId, buttonId, entityType, options = {}) {
        this.form = document.getElementById(formId);
        this.submitBtn = document.getElementById(buttonId);
        this.entityType = entityType;
        this.options = {
            modalId: null,
            showSuccessToast: true,
            reloadAfterSuccess: true,
            persistMessage: true,
            ...options
        };

        this.buttonLoader = new ButtonLoader(this.submitBtn);
        this.tableReloader = new TableReloader(entityType);
        this.modalHandler = this.options.modalId ? new ModalHandler(this.options.modalId) : null;
    }

    init() {
        if (!this.form || !this.submitBtn) {
            console.warn('Formulario o botón no encontrado');
            return;
        }

        this.submitBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            await this.handleSubmit();
        });
    }

    async handleSubmit() {
        const formData = new FormData(this.form);
        const url = this.form.action;

        console.log(`🔍 Enviando formulario de ${this.entityType}:`, url);

        this.buttonLoader.showLoading();

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();
            
            if (response.ok && result.success) {
                await this.handleSuccess(result);
            } else {
                this.handleError(result);
            }
            
        } catch (error) {
            this.handleNetworkError(error);
        } finally {
            this.buttonLoader.hideLoading();
        }
    }

    async handleSuccess(result) {
        if (this.options.persistMessage) {
            PersistentMessage.save(result.message, 'success');
        } else if (this.options.showSuccessToast) {
            showSuccessToast(result.message, {
                delay: 4000,
                position: 'top-end'
            });
        }
        
        if (this.modalHandler) {
            this.modalHandler.close();
        }
        
        if (this.options.reloadAfterSuccess) {
            setTimeout(() => {
                this.tableReloader.reload();
            }, 300);
        }
    }

    handleError(result) {
        showErrorToast(result.message || 'Error en la operación', {
            delay: 5000
        });
        
        if (result.errors) {
            ValidationErrorHandler.show(result.errors);
        }
    }

    handleNetworkError(error) {
        showErrorToast('Error de conexión: ' + error.message, {
            delay: 5000
        });
    }
}