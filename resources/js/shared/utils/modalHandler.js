// ✅ CREAR: resources/js/shared/utils/modalHandler.js

/**
 * Manejo de modales Bootstrap
 */
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