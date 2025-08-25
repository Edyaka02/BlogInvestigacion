export class EntityConfig {
    constructor(options) {
        this.entityType = options.entityType;
        this.entityRoute = options.entityRoute;
        this.urlBase = options.urlBase;

        this.format = options.format || 'table';  // 'table', 'cards', 'list'

        // ✅ DEFAULTS INTELIGENTES
        const route = options.entityRoute;
        this.modalFormId = options.modalFormId || `${route}Form`;
        this.modalId = options.modalId || `${route}Modal`;
        this.deleteModalId = options.deleteModalId || 'modalEliminar';
        this.deleteFormId = options.deleteFormId || 'formEliminar';
        this.searchFormId = options.searchFormId || 'search-Form';
        this.filtroFormId = options.filtroFormId || 'filtro-form';
        this.resultadosId = options.resultadosId || 'tabla-resultados';
        this.paginacionId = options.paginacionId || 'paginacion-container';

        // ✅ CONFIGURACIÓN DE AUTORES
        this.authorFieldsId = options.authorFieldsId || `authorFields_${route}`;
        this.addAuthorButtonId = options.addAuthorButtonId || `addAuthor_${route}`;
        this.removeAuthorButtonId = options.removeAuthorButtonId || `removeAuthor_${route}`;

        this.validate();
    }

    validate() {
        const required = ['entityType', 'entityRoute', 'urlBase'];
        for (const field of required) {
            if (!this[field]) {
                throw new Error(`EntityConfig: '${field}' es requerido`);
            }
        }

        // ✅ VALIDAR: Formato soportado
        const supportedFormats = ['table', 'cards', 'list'];
        if (!supportedFormats.includes(this.format)) {
            throw new Error(`EntityConfig: formato '${this.format}' no soportado. Use: ${supportedFormats.join(', ')}`);
        }
    }

    getAuthorConfig() {
        return {
            fieldsId: this.authorFieldsId,
            addButtonId: this.addAuthorButtonId,
            removeButtonId: this.removeAuthorButtonId
        };
    }

    static create(options) {
        return new EntityConfig(options);
    }
}