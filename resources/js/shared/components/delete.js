import {
    showErrorToast,
    // showConfirmToast
} from '../components/toast.js';

// import { PersistentMessage } from './formUtils.js';
import { PersistentMessage } from '../utils/persistentMessage.js';

export class DeleteHandler {
    constructor(options = {}) {
        this.config = {
            confirmMessage: '¿Estás seguro de que deseas eliminar este elemento?',
            confirmText: 'Eliminar',
            cancelText: 'Cancelar',
            entityType: 'elemento',
            ...options
        };
    }

    init() {
        // Escuchar clicks en botones de eliminar
        document.addEventListener('click', (e) => {
            const deleteBtn = e.target.closest('[data-bs-target="#modalEliminar"]');
            if (deleteBtn) {
                this.handleDeleteClick(deleteBtn);
            }
        });

        // Escuchar confirmación en modal de eliminar
        const confirmDeleteBtn = document.getElementById('confirmarEliminar');
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', () => {
                this.executeDelete();
            });
        }
    }

    handleDeleteClick(button) {
        // Guardar datos del elemento a eliminar
        this.currentElement = {
            id: button.getAttribute('data-id'),
            type: button.getAttribute('data-type') || this.config.entityType,
            route: button.getAttribute('data-route'),
            name: button.getAttribute('data-name') || 'este elemento'
        };

        // Actualizar contenido del modal de confirmación
        this.updateConfirmModal();
    }

    updateConfirmModal() {
        // Actualizar texto del modal
        const modalBody = document.querySelector('#modalEliminar .modal-body p');
        if (modalBody) {
            modalBody.textContent = `¿Estás seguro de que deseas eliminar ${this.currentElement.type} "${this.currentElement.name}"?`;
        }

        // Actualizar título del modal
        const modalTitle = document.querySelector('#modalEliminar .modal-title');
        if (modalTitle) {
            modalTitle.textContent = `Eliminar ${this.currentElement.type}`;
        }
    }

    async executeDelete() {
        if (!this.currentElement) {
            console.error('No hay elemento seleccionado para eliminar');
            return;
        }

        const { id, route } = this.currentElement;
        const url = `/${route}/${id}`;

        // Mostrar estado de carga
        const confirmBtn = document.getElementById('confirmarEliminar');
        const originalText = confirmBtn.textContent;
        const originalDisabled = confirmBtn.disabled;

        confirmBtn.textContent = 'Eliminando...';
        confirmBtn.disabled = true;

        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // Guardar mensaje de éxito para después de recarga
                PersistentMessage.save(result.message, 'success');

                // Cerrar modal
                this.closeModal();

                // Recargar tabla después de un breve delay
                setTimeout(() => {
                    this.reloadTable();
                }, 300);

            } else {
                // Mostrar error en toast
                showErrorToast(result.message || 'Error al eliminar el elemento', {
                    delay: 5000
                });

                this.closeModal();
            }

        } catch (error) {
            console.error('Error en eliminación:', error);
            
            showErrorToast('Error de conexión al eliminar. Inténtalo de nuevo.', {
                delay: 5000
            });

            this.closeModal();

        } finally {
            // Restaurar estado del botón
            confirmBtn.textContent = originalText;
            confirmBtn.disabled = originalDisabled;
        }
    }

    closeModal() {
        const modal = document.getElementById('modalEliminar');
        if (modal && typeof bootstrap !== 'undefined') {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) {
                bsModal.hide();
            }
        }
    }

    reloadTable() {
        // Intentar usar función específica primero
        const reloadFunctionName = `recargarTabla${this.capitalize(this.currentElement.route || this.config.entityType)}`;
        
        if (typeof window[reloadFunctionName] === 'function') {
            console.log(`✅ Usando función específica: ${reloadFunctionName}`);
            window[reloadFunctionName]();
            return;
        }

        // Disparar evento personalizado
        const reloadEvent = new CustomEvent('reloadTable', {
            detail: { 
                entityType: this.currentElement.route || this.config.entityType,
                action: 'delete'
            }
        });
        document.dispatchEvent(reloadEvent);

        // Fallback: recargar página
        setTimeout(() => {
            console.log('🔄 Fallback: Recargando página completa');
            window.location.reload();
        }, 500);
    }

    capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
}

// ✅ FUNCIÓN AUXILIAR: Para uso directo sin clase
export function initDeleteHandlers(options = {}) {
    const deleteHandler = new DeleteHandler(options);
    deleteHandler.init();
    return deleteHandler;
}