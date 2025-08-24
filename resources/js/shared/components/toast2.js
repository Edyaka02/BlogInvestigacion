// ✅ CREAR: resources/js/shared/components/toast2.js

// Importar Sonner
import { toast } from 'sonner'

// ✅ CLASE: Sonner Toast personalizada
export class SonnerToast {
    static init() {
        // Verificar si ya existe el contenedor
        if (!document.querySelector('[data-sonner-toaster]')) {
            console.log('🎯 Inicializando Sonner Toaster...');
            
            // Crear contenedor para Sonner
            const toasterDiv = document.createElement('div');
            toasterDiv.setAttribute('data-sonner-toaster', '');
            toasterDiv.style.cssText = `
                position: fixed;
                top: 0;
                right: 0;
                z-index: 9999;
                pointer-events: none;
            `;
            document.body.appendChild(toasterDiv);
        }
    }

    static success(message, options = {}) {
        this.init();
        
        return toast.success(message, {
            duration: 4000,
            position: 'top-right',
            style: {
                background: 'linear-gradient(135deg, var(--color-primario, #28a745) 0%, var(--color-primario-oscuro, #20c997) 100%)',
                color: 'white',
                border: 'none',
                borderRadius: '12px',
                fontFamily: 'var(--font-text, system-ui)',
                fontWeight: '500',
                fontSize: '0.95rem',
                boxShadow: '0 8px 32px rgba(0,0,0,0.12)',
                backdropFilter: 'blur(8px)',
                padding: '16px 20px',
                minWidth: '320px',
                maxWidth: '500px'
            },
            className: 'sonner-toast-success',
            description: options.description || null,
            action: options.action || null,
            cancel: options.cancel || null,
            ...options
        });
    }

    static error(message, options = {}) {
        this.init();
        
        return toast.error(message, {
            duration: 5000,
            position: 'top-right',
            style: {
                background: 'linear-gradient(135deg, #dc3545 0%, #fd7e14 100%)',
                color: 'white',
                border: 'none',
                borderRadius: '12px',
                fontFamily: 'var(--font-text, system-ui)',
                fontWeight: '500',
                fontSize: '0.95rem',
                boxShadow: '0 8px 32px rgba(220,53,69,0.15)',
                backdropFilter: 'blur(8px)',
                padding: '16px 20px',
                minWidth: '320px',
                maxWidth: '500px'
            },
            className: 'sonner-toast-error',
            description: options.description || null,
            action: options.action || null,
            cancel: options.cancel || null,
            ...options
        });
    }

    static info(message, options = {}) {
        this.init();
        
        return toast.info(message, {
            duration: 4000,
            position: 'top-right',
            style: {
                background: 'linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%)',
                color: 'white',
                border: 'none',
                borderRadius: '12px',
                fontFamily: 'var(--font-text, system-ui)',
                fontWeight: '500',
                fontSize: '0.95rem',
                boxShadow: '0 8px 32px rgba(23,162,184,0.15)',
                backdropFilter: 'blur(8px)',
                padding: '16px 20px',
                minWidth: '320px',
                maxWidth: '500px'
            },
            className: 'sonner-toast-info',
            description: options.description || null,
            ...options
        });
    }

    static warning(message, options = {}) {
        this.init();
        
        return toast.warning(message, {
            duration: 4000,
            position: 'top-right',
            style: {
                background: 'linear-gradient(135deg, #ffc107 0%, #fd7e14 100%)',
                color: '#212529',
                border: 'none',
                borderRadius: '12px',
                fontFamily: 'var(--font-text, system-ui)',
                fontWeight: '500',
                fontSize: '0.95rem',
                boxShadow: '0 8px 32px rgba(255,193,7,0.15)',
                backdropFilter: 'blur(8px)',
                padding: '16px 20px',
                minWidth: '320px',
                maxWidth: '500px'
            },
            className: 'sonner-toast-warning',
            description: options.description || null,
            ...options
        });
    }

    static loading(message, options = {}) {
        this.init();
        
        return toast.loading(message, {
            position: 'top-right',
            style: {
                background: 'linear-gradient(135deg, #6c757d 0%, #495057 100%)',
                color: 'white',
                border: 'none',
                borderRadius: '12px',
                fontFamily: 'var(--font-text, system-ui)',
                fontWeight: '500',
                fontSize: '0.95rem',
                padding: '16px 20px',
                minWidth: '320px'
            },
            className: 'sonner-toast-loading',
            ...options
        });
    }

    static promise(promise, messages, options = {}) {
        this.init();
        
        return toast.promise(promise, {
            loading: messages.loading || 'Cargando...',
            success: (data) => {
                return messages.success || 'Completado exitosamente';
            },
            error: messages.error || 'Error en la operación',
            position: 'top-right',
            style: {
                borderRadius: '12px',
                fontFamily: 'var(--font-text, system-ui)',
                fontWeight: '500',
                fontSize: '0.95rem',
                padding: '16px 20px',
                minWidth: '320px'
            },
            ...options
        });
    }

    static custom(jsx, options = {}) {
        this.init();
        
        return toast.custom(jsx, {
            position: 'top-right',
            duration: 4000,
            ...options
        });
    }

    static dismiss(toastId) {
        return toast.dismiss(toastId);
    }

    static dismissAll() {
        return toast.dismiss();
    }
}

// ✅ FUNCIONES: De compatibilidad para usar en tu código actual
export function showSuccessToast2(message, options = {}) {
    return SonnerToast.success(message, options);
}

export function showErrorToast2(message, options = {}) {
    return SonnerToast.error(message, options);
}

export function showInfoToast2(message, options = {}) {
    return SonnerToast.info(message, options);
}

export function showWarningToast2(message, options = {}) {
    return SonnerToast.warning(message, options);
}

export function showLoadingToast2(message, options = {}) {
    return SonnerToast.loading(message, options);
}

// ✅ EJEMPLOS: De uso avanzado
export const ToastExamples = {
    // Toast con descripción
    successWithDescription: (title, description) => {
        return SonnerToast.success(title, {
            description: description,
            duration: 5000
        });
    },

    // Toast con acción
    successWithAction: (message, actionLabel, actionCallback) => {
        return SonnerToast.success(message, {
            action: {
                label: actionLabel,
                onClick: actionCallback
            }
        });
    },

    // Toast con botón cancelar
    withCancel: (message) => {
        return SonnerToast.error(message, {
            cancel: {
                label: 'Deshacer',
                onClick: () => console.log('Acción deshecha')
            }
        });
    },

    // Toast de promesa (para operaciones async)
    promiseExample: (asyncOperation) => {
        return SonnerToast.promise(asyncOperation, {
            loading: 'Procesando...',
            success: 'Operación completada exitosamente',
            error: 'Error al procesar la operación'
        });
    },

    // Toast personalizado
    customToast: () => {
        return SonnerToast.custom(
            `<div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; background: var(--color-primario); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-check" style="color: white;"></i>
                </div>
                <div>
                    <div style="font-weight: 600; margin-bottom: 4px;">Operación Exitosa</div>
                    <div style="font-size: 0.85rem; opacity: 0.8;">El artículo ha sido guardado correctamente</div>
                </div>
            </div>`,
            { duration: 6000 }
        );
    }
};

// ✅ HACER: Disponible globalmente para pruebas
window.SonnerToast = SonnerToast;
window.Toast2 = {
    success: showSuccessToast2,
    error: showErrorToast2,
    info: showInfoToast2,
    warning: showWarningToast2,
    loading: showLoadingToast2,
    examples: ToastExamples,
    dismiss: SonnerToast.dismiss,
    dismissAll: SonnerToast.dismissAll
};

// ✅ AUTO-INICIALIZAR: Sonner cuando se carga el módulo
SonnerToast.init();

console.log('🎯 Sonner Toast2.js cargado - Prueba con: Toast2.success("Mensaje de prueba")');

export default SonnerToast;