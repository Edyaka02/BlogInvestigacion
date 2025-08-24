// ✅ CREAR: resources/js/shared/utils/tableReloader.js

/**
 * Sistema de recarga de tablas
 */
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