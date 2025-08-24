// ✅ CREAR: resources/js/shared/utils/persistentMessage.js
import {
    showSuccessToast,
    showErrorToast
} from '../components/toast.js';

/**
 * Sistema de mensajes persistentes para mostrar después de recargas
 */
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