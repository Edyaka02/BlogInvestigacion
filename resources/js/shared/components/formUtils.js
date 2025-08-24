// ✅ CREAR: resources/js/shared/utils/formUtils.js
/**
 * Utilidades para formularios y operaciones CRUD
 */

import {
    showSuccessToast,
    showErrorToast,
    showValidationErrorsToast
} from './toast.js';

// ✅ 1. SISTEMA DE MENSAJES PERSISTENTES
export class PersistentMessage {
    static save(message, type = 'success') {
        localStorage.setItem('toastMessage', JSON.stringify({
            message,
            type,
            timestamp: Date.now()
        }));
    }

    static show() {
        try {
            const savedToast = localStorage.getItem('toastMessage');
            
            if (savedToast) {
                const toastData = JSON.parse(savedToast);
                
                // Verificar que el mensaje no sea muy viejo (máximo 10 segundos)
                const now = Date.now();
                const messageAge = now - toastData.timestamp;
                
                if (messageAge < 10000) { // 10 segundos
                    console.log('✅ Mostrando toast después de recarga:', toastData.message);
                    
                    // Mostrar el toast
                    if (toastData.type === 'success') {
                        showSuccessToast(toastData.message, {
                            delay: 4000,
                            position: 'top-end'
                        });
                    } else if (toastData.type === 'error') {
                        showErrorToast(toastData.message, {
                            delay: 5000,
                            position: 'top-end'
                        });
                    }
                }
                
                // Limpiar el mensaje guardado
                localStorage.removeItem('toastMessage');
            }
        } catch (error) {
            console.error('❌ Error mostrando toast pendiente:', error);
            localStorage.removeItem('toastMessage');
        }
    }

    static clear() {
        localStorage.removeItem('toastMessage');
    }
}

// ✅ 2. MANEJO DE ESTADOS DE LOADING
// 
export class ButtonLoader {
    constructor(button, config = {}) {
        this.button = button;
        this.config = {
            editIcon: 'fa-solid fa-pen-to-square',
            createIcon: 'fa-solid fa-upload',
            loadingIcon: 'fas fa-spinner fa-spin',
            editText: 'Actualizar',
            createText: 'Crear',
            loadingText: 'Procesando...',
            ...config
        };
        
        this.icon = button.querySelector('i');
        this.text = button.querySelector('span');
        this.originalState = {
            disabled: button.disabled,
            iconClass: this.icon?.className || '',
            text: this.text?.textContent || ''
        };
    }

    showLoading() {
        this.button.disabled = true;
        if (this.icon) this.icon.className = this.config.loadingIcon;
        if (this.text) this.text.textContent = this.config.loadingText;
    }

    hideLoading() {
        this.button.disabled = false;
        const isEdit = this.button.getAttribute('data-modo') === 'editar';
        
        if (this.icon) {
            this.icon.className = isEdit ? this.config.editIcon : this.config.createIcon;
        }
        if (this.text) {
            this.text.textContent = isEdit ? this.config.editText : this.config.createText;
        }
    }

    reset() {
        this.button.disabled = this.originalState.disabled;
        if (this.icon) this.icon.className = this.originalState.iconClass;
        if (this.text) this.text.textContent = this.originalState.text;
    }
}

// ✅ 3. MANEJO DE ERRORES DE VALIDACIÓN
// Esta clase maneja la visualización de errores de validación en formularios
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
                // input.classList.add('is-invalid');

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
        
        // ✅ AGREGAR: Limpiar también mensajes de texto rojo
        document.querySelectorAll('.text-danger').forEach(el => {
            if (el.textContent.includes('field is required')) {
                el.textContent = '';
                el.style.display = 'none';
            }
        });
    }
}

// ✅ 4. SISTEMA DE RECARGA DE TABLAS
export class TableReloader {
    constructor(entityType, globalFunctionName = null) {
        this.entityType = entityType;
        this.globalFunctionName = globalFunctionName || `recargarTabla${this.capitalize(entityType)}`;
    }

    capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    async reload() {
        console.log(`🔄 Recargando tabla de ${this.entityType}...`);
        
        try {
            // Opción 1: Función global específica
            if (typeof window[this.globalFunctionName] === 'function') {
                console.log('✅ Usando función global de recarga');
                window[this.globalFunctionName]();
                return;
            }

            // Opción 2: Evento personalizado
            const reloadEvent = new CustomEvent('reloadTable', {
                detail: { entityType: this.entityType }
            });
            document.dispatchEvent(reloadEvent);
            console.log('✅ Evento de recarga disparado');

            // Opción 3: Fallback - recarga completa
            setTimeout(() => {
                console.log('🔄 Fallback: Recargando página completa');
                window.location.reload();
            }, 500);

        } catch (error) {
            console.error('❌ Error recargando tabla:', error);
            window.location.reload();
        }
    }

    reloadImmediately() {
        window.location.reload();
    }
}

// ✅ 5. MANEJO DE MODALES BOOTSTRAP
export class ModalHandler {
    constructor(modalId) {
        this.modalId = modalId;
        this.modalElement = document.getElementById(modalId);
    }

    close() {
        if (!this.modalElement) return;

        try {
            const modal = bootstrap.Modal.getInstance(this.modalElement);
            if (modal) {
                console.log('✅ Cerrando modal con instancia existente');
                modal.hide();
                return;
            }

            console.log('✅ Creando nueva instancia de modal');
            const newModal = new bootstrap.Modal(this.modalElement);
            newModal.hide();

            setTimeout(() => {
                const closeBtn = this.modalElement.querySelector('[data-bs-dismiss="modal"]');
                if (closeBtn) {
                    console.log('✅ Disparando click en botón cerrar');
                    closeBtn.click();
                }
            }, 100);

        } catch (error) {
            console.error('❌ Error cerrando modal:', error);
            
            this.modalElement.classList.remove('show');
            this.modalElement.style.display = 'none';
            document.body.classList.remove('modal-open');

            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }
    }

    show() {
        if (!this.modalElement) return;

        try {
            const modal = bootstrap.Modal.getInstance(this.modalElement) || new bootstrap.Modal(this.modalElement);
            modal.show();
        } catch (error) {
            console.error('❌ Error abriendo modal:', error);
        }
    }
}

// ✅ 6. CONTROLADOR DE FORMULARIOS AJAX
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