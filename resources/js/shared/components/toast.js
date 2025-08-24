// ✅ CREAR: resources/js/shared/components/toast.js
/**
 * Sistema de toasts genérico usando Bootstrap
 * @author Tu nombre
 * @version 1.0.0
 */

// ✅ FUNCIÓN PRINCIPAL: Mostrar toast
export function showToast(message, type = 'success', options = {}) {
    const config = {
        autohide: true,
        delay: 4000,
        position: 'top-end', // top-start, top-center, top-end, bottom-start, etc.
        showIcon: true,
        closable: true,
        ...options
    };

    const toastId = 'toast-' + Date.now();
    const iconClass = config.showIcon ? getToastIcon(type) : '';
    const bgClass = getToastBackground(type);
    const textClass = getToastTextClass(type);

    const toastHTML = `
        <div id="${toastId}" class="toast align-items-center ${textClass} ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${config.showIcon ? `<i class="${iconClass} me-2"></i>` : ''}
                    ${message}
                </div>
                ${config.closable ? `<button type="button" class="btn-close ${getCloseButtonClass(type)} me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>` : ''}
            </div>
        </div>
    `;

    // Obtener o crear contenedor de toasts
    let toastContainer = getToastContainer(config.position);

    // Agregar toast al contenedor
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);

    // Obtener el elemento toast y mostrarlo
    const toastElement = document.getElementById(toastId);
    
    // Verificar que Bootstrap esté disponible
    if (typeof bootstrap === 'undefined') {
        console.warn('Bootstrap no está disponible. Usando fallback para toasts.');
        showFallbackToast(toastElement, message, type, config.delay);
        return toastElement;
    }

    const toast = new bootstrap.Toast(toastElement, {
        autohide: config.autohide,
        delay: config.delay
    });

    // Mostrar el toast
    toast.show();

    // Limpiar el toast después de ocultarlo
    toastElement.addEventListener('hidden.bs.toast', function () {
        toastElement.remove();
        
        // Si no hay más toasts, limpiar el contenedor
        cleanupEmptyContainer(toastContainer);
    });

    console.log(`✅ Toast ${type} mostrado:`, message);
    return toast;
}

// ✅ FUNCIONES ESPECÍFICAS PARA CADA TIPO
export function showSuccessToast(message, options = {}) {
    return showToast(message, 'success', options);
}

export function showErrorToast(message, options = {}) {
    return showToast(message, 'error', { delay: 6000, ...options });
}

export function showWarningToast(message, options = {}) {
    return showToast(message, 'warning', { delay: 5000, ...options });
}

export function showInfoToast(message, options = {}) {
    return showToast(message, 'info', options);
}

// ✅ FUNCIÓN ESPECIAL: Toast con progreso
export function showProgressToast(message, duration = 4000, options = {}) {
    const config = {
        showProgress: true,
        progressColor: 'white',
        ...options
    };

    const toastId = 'toast-progress-' + Date.now();
    
    const toastHTML = `
        <div id="${toastId}" class="toast align-items-center text-white bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-spinner fa-spin me-2"></i>
                    ${message}
                    ${config.showProgress ? `
                        <div class="progress mt-2" style="height: 3px;">
                            <div class="progress-bar bg-${config.progressColor}" role="progressbar" style="width: 0%"></div>
                        </div>
                    ` : ''}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    let toastContainer = getToastContainer('top-end');
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);
    
    const toastElement = document.getElementById(toastId);
    const progressBar = toastElement.querySelector('.progress-bar');
    
    if (typeof bootstrap === 'undefined') {
        showFallbackToast(toastElement, message, 'info', duration);
        return toastElement;
    }

    const toast = new bootstrap.Toast(toastElement, {
        autohide: false
    });
    
    toast.show();
    
    // Animar progreso
    let progress = 0;
    const interval = setInterval(() => {
        progress += (100 / (duration / 100));
        if (progressBar) {
            progressBar.style.width = `${Math.min(progress, 100)}%`;
        }
        
        if (progress >= 100) {
            clearInterval(interval);
            setTimeout(() => {
                toast.hide();
            }, 500);
        }
    }, 100);
    
    // Limpiar cuando se oculte
    toastElement.addEventListener('hidden.bs.toast', function () {
        clearInterval(interval);
        toastElement.remove();
        cleanupEmptyContainer(toastContainer);
    });
    
    return {
        toast,
        hide: () => {
            clearInterval(interval);
            toast.hide();
        }
    };
}

// ✅ FUNCIÓN: Toast para validación de formularios
export function showValidationErrorsToast(errors, options = {}) {
    const errorCount = Object.keys(errors).length;
    const message = options.message || `Se encontraron ${errorCount} errores en el formulario. Por favor revisa los campos marcados.`;
    
    return showWarningToast(message, {
        delay: 6000,
        ...options
    });
}

// ✅ FUNCIONES AUXILIARES
function getToastIcon(type) {
    const icons = {
        'success': 'fas fa-check-circle',
        'error': 'fas fa-exclamation-circle',
        'warning': 'fas fa-exclamation-triangle',
        'info': 'fas fa-info-circle',
        'primary': 'fas fa-info-circle',
        'secondary': 'fas fa-cog',
        'dark': 'fas fa-moon',
        'light': 'fas fa-sun'
    };
    return icons[type] || icons['info'];
}

function getToastBackground(type) {
    const backgrounds = {
        'success': 'bg-success',
        'error': 'bg-danger',
        'warning': 'bg-warning',
        'info': 'bg-info',
        'primary': 'bg-primary',
        'secondary': 'bg-secondary',
        'dark': 'bg-dark',
        'light': 'bg-light'
    };
    return backgrounds[type] || backgrounds['info'];
}

function getToastTextClass(type) {
    const textClasses = {
        'light': 'text-dark',
        'warning': 'text-dark'
    };
    return textClasses[type] || 'text-white';
}

function getCloseButtonClass(type) {
    const closeClasses = {
        'light': 'btn-close-dark',
        'warning': 'btn-close-dark'
    };
    return closeClasses[type] || 'btn-close-white';
}

function getToastContainer(position = 'top-end') {
    const containerId = `toast-container-${position}`;
    let container = document.getElementById(containerId);
    
    if (!container) {
        container = createToastContainer(position, containerId);
    }
    
    return container;
}

function createToastContainer(position, containerId) {
    const container = document.createElement('div');
    container.id = containerId;
    container.style.zIndex = '9999';
    
    // Configurar clases según posición
    const positionClasses = {
        'top-start': 'toast-container position-fixed top-0 start-0 p-3',
        'top-center': 'toast-container position-fixed top-0 start-50 translate-middle-x p-3',
        'top-end': 'toast-container position-fixed top-0 end-0 p-3',
        'middle-start': 'toast-container position-fixed top-50 start-0 translate-middle-y p-3',
        'middle-center': 'toast-container position-fixed top-50 start-50 translate-middle p-3',
        'middle-end': 'toast-container position-fixed top-50 end-0 translate-middle-y p-3',
        'bottom-start': 'toast-container position-fixed bottom-0 start-0 p-3',
        'bottom-center': 'toast-container position-fixed bottom-0 start-50 translate-middle-x p-3',
        'bottom-end': 'toast-container position-fixed bottom-0 end-0 p-3'
    };
    
    container.className = positionClasses[position] || positionClasses['top-end'];
    document.body.appendChild(container);
    return container;
}

function cleanupEmptyContainer(container) {
    // Si no hay toasts en el contenedor, removerlo después de un delay
    setTimeout(() => {
        if (container && container.children.length === 0) {
            container.remove();
        }
    }, 1000);
}

// ✅ FALLBACK: Para cuando Bootstrap no está disponible
function showFallbackToast(toastElement, message, type, delay) {
    console.warn('Usando fallback para toast');
    
    // Mostrar como una alerta simple
    toastElement.style.display = 'block';
    toastElement.style.opacity = '1';
    
    // Auto-hide después del delay
    setTimeout(() => {
        toastElement.style.opacity = '0';
        setTimeout(() => {
            toastElement.remove();
        }, 300);
    }, delay);
}

// ✅ FUNCIÓN UTILITARIA: Limpiar todos los toasts
export function clearAllToasts() {
    const toastContainers = document.querySelectorAll('[id^="toast-container"]');
    toastContainers.forEach(container => {
        container.remove();
    });
}

// ✅ FUNCIÓN UTILITARIA: Toast con confirmación
export function showConfirmToast(message, onConfirm, onCancel, options = {}) {
    const config = {
        confirmText: 'Confirmar',
        cancelText: 'Cancelar',
        ...options
    };

    const toastId = 'toast-confirm-' + Date.now();
    
    const toastHTML = `
        <div id="${toastId}" class="toast align-items-center text-white bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body">
                <i class="fas fa-question-circle me-2"></i>
                ${message}
                <div class="mt-2">
                    <button type="button" class="btn btn-sm btn-light me-2" data-action="confirm">
                        ${config.confirmText}
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-light" data-action="cancel">
                        ${config.cancelText}
                    </button>
                </div>
            </div>
        </div>
    `;
    
    let toastContainer = getToastContainer('top-center');
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);
    
    const toastElement = document.getElementById(toastId);
    
    // Event listeners para botones
    toastElement.addEventListener('click', function(e) {
        if (e.target.dataset.action === 'confirm') {
            onConfirm?.();
            toastElement.remove();
        } else if (e.target.dataset.action === 'cancel') {
            onCancel?.();
            toastElement.remove();
        }
    });
    
    if (typeof bootstrap !== 'undefined') {
        const toast = new bootstrap.Toast(toastElement, { autohide: false });
        toast.show();
        return toast;
    } else {
        showFallbackToast(toastElement, message, 'warning', 10000);
        return toastElement;
    }
}