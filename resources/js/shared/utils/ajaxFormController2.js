// ✅ CREAR: resources/js/shared/utils/ajaxFormController2.js
import { ButtonLoader } from './buttonLoader.js';
import { TableReloader } from './tableReloader.js';
import { ModalHandler } from './modalHandler.js';
import { ValidationErrorHandler } from './validationHandler.js';
import { showSuccessToast2, showErrorToast2 } from '../components/toast2.js';

export class AjaxFormController2 {
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
        if (!this.form || !this.submitBtn) return;

        this.submitBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            await this.handleSubmit();
        });
    }

    async handleSubmit() {
        const formData = new FormData(this.form);
        const url = this.form.action;

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
        // ✅ GUARDAR: Mensaje para Sonner
        if (this.options.persistMessage) {
            localStorage.setItem('sonnerToastMessage', JSON.stringify({
                message: result.message,
                type: 'success'
            }));
        } else if (this.options.showSuccessToast) {
            showSuccessToast2(result.message);
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
        showErrorToast2(result.message || 'Error en la operación');
        
        if (result.errors) {
            ValidationErrorHandler.show(result.errors);
        }
    }

    handleNetworkError(error) {
        showErrorToast2('Error de conexión: ' + error.message);
    }
}