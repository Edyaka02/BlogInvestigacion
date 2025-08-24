/**
 * CONFIGURACIÓN GENÉRICA DE ENTIDAD
 */
export class EntityConfig {
    // constructor(options) {
    //     this.entityType = options.entityType;           // 'Artículo'
    //     this.entityRoute = options.entityRoute;         // 'articulos'
    //     this.urlBase = options.urlBase;                 // '/dashboard/articulos'
    //     this.searchFormId = options.searchFormId;       // 'search-Form'
    //     this.filtroFormId = options.filtroFormId;       // 'filtro-form'
    //     this.resultadosId = options.resultadosId;       // 'tabla-resultados'
    //     this.paginacionId = options.paginacionId;       // 'paginacion-container'
    //     this.modalFormId = options.modalFormId;         // 'articuloForm'
    //     this.modalId = options.modalId;                 // 'articuloModal'
    //     this.deleteModalId = options.deleteModalId;     // 'modalEliminar'
    //     this.deleteFormId = options.deleteFormId;       // 'formEliminar'
    //     this.modalHandlerPath = options.modalHandlerPath; // './modal.js'
    //     this.modalHandlerFunction = options.modalHandlerFunction; // 'initArticuloModalHandlers'
        
    //     // Validar configuración requerida
    //     this.validate();
    // }
    constructor(options) {
        // ✅ CAMPOS ESENCIALES
        this.entityType = options.entityType;
        this.entityRoute = options.entityRoute;
        this.urlBase = options.urlBase;
        
        // ✅ CAMPOS PARA MODALES (con defaults inteligentes)
        this.modalFormId = options.modalFormId || `${options.entityRoute}Form`;
        this.modalId = options.modalId || `${options.entityRoute}Modal`;
        
        // ✅ CAMPOS PARA ELIMINAR (con defaults)
        this.deleteModalId = options.deleteModalId || 'modalEliminar';
        this.deleteFormId = options.deleteFormId || 'formEliminar';
        
        // ✅ CAMPOS PARA TABLA (solo si realmente los usas)
        this.searchFormId = options.searchFormId || 'search-Form';
        this.filtroFormId = options.filtroFormId || 'filtro-form';
        this.resultadosId = options.resultadosId || 'tabla-resultados';
        this.paginacionId = options.paginacionId || 'paginacion-container';
        
        this.validate();
    }

    /**
     * Validar que la configuración tenga los campos requeridos
     */
    validate() {
        const required = ['entityType', 'entityRoute', 'urlBase'];
        for (const field of required) {
            if (!this[field]) {
                throw new Error(`EntityConfig: Campo requerido '${field}' no proporcionado`);
            }
        }
    }

    /**
     * Obtener configuración por defecto
     */
    // static getDefaults() {
    //     return {
    //         searchFormId: 'search-Form',
    //         filtroFormId: 'filtro-form',
    //         resultadosId: 'tabla-resultados',
    //         paginacionId: 'paginacion-container',
    //         deleteModalId: 'modalEliminar',
    //         deleteFormId: 'formEliminar'
    //     };
    // }

    /**
     * Crear configuración con valores por defecto
     */
    static create(options) {
        // const defaults = EntityConfig.getDefaults();
        // return new EntityConfig({ ...defaults, ...options });
        return new EntityConfig(options);
    }
}