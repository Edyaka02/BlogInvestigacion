// ✅ CREAR: resources/js/shared/utils/buttonLoader.js

/**
 * Manejo de estados de loading en botones
 */
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