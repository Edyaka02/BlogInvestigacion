import { loadDataResults } from '../../components/data/dataLoader.js';

/**
 * CLASE GENÉRICA PARA MANEJAR ENTIDADES (MULTI-FORMATO)
 */
export class EntityManager {
    constructor(config) {
        this.config = config;
        this.dataController = null;
    }

    /**
     * Función genérica para recargar datos con AJAX
     */
    async reloadData() {
        console.log(`🔄 Recargando ${this.config.format} de ${this.config.entityRoute}...`);

        const dataContainer = document.getElementById(this.config.resultadosId);
        const paginacionContainer = document.getElementById(this.config.paginacionId);

        if (!dataContainer) {
            console.error('❌ Contenedor de datos no encontrado');
            return Promise.reject('Contenedor no encontrado');
        }

        // Mostrar indicador de carga
        dataContainer.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>';

        // Obtener filtros actuales
        const searchForm = document.getElementById(this.config.searchFormId);
        const filtroForm = document.getElementById(this.config.filtroFormId);

        const formData = new FormData();

        // Agregar datos de formularios de búsqueda y filtros
        if (searchForm) {
            const searchData = new FormData(searchForm);
            for (let [key, value] of searchData.entries()) {
                formData.append(key, value);
            }
        }

        if (filtroForm) {
            const filtroData = new FormData(filtroForm);
            for (let [key, value] of filtroData.entries()) {
                formData.append(key, value);
            }
        }

        try {
            const response = await fetch(this.config.urlBase, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'text/html'
                }
            });

            if (!response.ok) {
                throw new Error(`Error ${response.status}`);
            }

            const html = await response.text();

            if (!html.trim()) {
                throw new Error('Respuesta HTML vacía');
            }

            // Actualizar contenido
            dataContainer.innerHTML = html;

            // Actualizar paginación si existe
            if (paginacionContainer) {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                const nuevaPaginacion = tempDiv.querySelector('.pagination');

                if (nuevaPaginacion) {
                    paginacionContainer.innerHTML = nuevaPaginacion.outerHTML;
                }
            }

            console.log(`✅ ${this.config.format} de ${this.config.entityRoute} recargado`);
            return html;

        } catch (error) {
            console.error(`❌ Error al recargar ${this.config.format} de ${this.config.entityRoute}:`, error);

            const entityName = this.config.entityRoute.charAt(0).toUpperCase() + this.config.entityRoute.slice(1);
            dataContainer.innerHTML = `
                <div class="alert alert-danger">
                    <h6>Error al cargar datos</h6>
                    <p><strong>Detalle:</strong> ${error.message}</p>
                    <button onclick="window.reloadData${entityName}()" 
                            class="btn btn-sm btn-primary ms-2">
                        <i class="fas fa-sync"></i> Reintentar
                    </button>
                    <button onclick="window.location.reload()" 
                            class="btn btn-sm btn-secondary ms-2">
                        <i class="fas fa-refresh"></i> Recargar página
                    </button>
                </div>
            `;
            throw error;
        }
    }

    /**
     * Configurar visualización con renderizado (GENÉRICO)
     */
    configureDisplay(renderConfig) {
        if (!renderConfig) {
            console.warn('⚠️ No se proporcionó configuración de renderizado');
            return null;
        }

        // ✅ CONFIGURAR: Según el formato especificado
        const config = {
            urlBase: this.config.urlBase,
            buscadorFormId: this.config.searchFormId,
            filtroFormId: this.config.filtroFormId,
            resultadosDivId: this.config.resultadosId,
            paginacionDivId: this.config.paginacionId,
            key: this.config.entityRoute,
            cargarInicialmente: true,
            format: this.config.format,        // ✅ USAR: Formato de la configuración
            entityType: this.config.entityType,
            entityRoute: this.config.entityRoute
        };

        // ✅ AGREGAR: Funciones de renderizado según formato
        switch (this.config.format) {
            case 'table':
                config.renderHeader = renderConfig.renderHeader;
                config.renderRow = renderConfig.renderRow;
                break;
            
            case 'cards':
                config.renderCard = renderConfig.renderCard;
                config.containerClass = renderConfig.containerClass || 'g-3';
                break;
            
            case 'list':
                config.renderListItem = renderConfig.renderListItem;
                config.containerClass = renderConfig.containerClass || '';
                break;
        }

        this.dataController = loadDataResults(config);

        console.log(`✅ ${this.config.format} de ${this.config.entityRoute} configurado`);
        return this.dataController;
    }

    /**
     * Crear función global básica de recarga (GENÉRICA)
     */
    createGlobalFunctions() {
        const entityName = this.config.entityRoute.charAt(0).toUpperCase() + this.config.entityRoute.slice(1);

        // ✅ FUNCIÓN: Genérica que funciona para cualquier formato
        window[`reloadData${entityName}`] = () => {
            if (this.dataController && typeof this.dataController.recargar === 'function') {
                this.dataController.recargar();
            } else {
                this.reloadData();
            }
        };

        // ✅ MANTENER: Compatibilidad con nombres anteriores
        window.dispararRecargaTabla = () => window[`reloadData${entityName}`]();
        window[`reloadTable${entityName}`] = () => window[`reloadData${entityName}`]();

        console.log(`✅ Funciones globales genéricas creadas para ${this.config.entityType}`);

        return {
            reloadFunction: `reloadData${entityName}`,
            legacyFunctions: ['dispararRecargaTabla', `reloadTable${entityName}`]
        };
    }

    /**
     * Configurar event listeners (GENÉRICO)
     */
    configureEventListeners() {
        const entityName = this.config.entityRoute.charAt(0).toUpperCase() + this.config.entityRoute.slice(1);

        // Event listener para eventos de recarga específicos
        document.addEventListener(`reloadData${entityName}`, () => {
            this.reloadData();
        });

        // Event listener genérico
        document.addEventListener('reloadData', (e) => {
            if (e.detail?.entityRoute === this.config.entityRoute) {
                this.reloadData();
            }
        });

        console.log(`✅ Event listeners configurados para ${this.config.entityRoute}`);
    }

    /**
     * Inicializar sistema (GENÉRICO)
     */
    initialize(renderConfig) {
        console.log(`🚀 Inicializando sistema ${this.config.format} para ${this.config.entityRoute}...`);

        // ✅ CONFIGURAR: Visualización según formato
        this.configureDisplay(renderConfig);

        // ✅ CREAR: Funciones globales genéricas
        const globalFunctions = this.createGlobalFunctions();

        // ✅ CONFIGURAR: Event listeners
        this.configureEventListeners();

        console.log(`✅ Sistema ${this.config.format} para ${this.config.entityRoute} inicializado`);

        return {
            manager: this,
            globalFunctions,
            dataController: this.dataController
        };
    }

    /**
     * Destruir y limpiar recursos (GENÉRICO)
     */
    destroy() {
        const entityName = this.config.entityRoute.charAt(0).toUpperCase() + this.config.entityRoute.slice(1);
        
        // Limpiar funciones globales
        delete window[`reloadData${entityName}`];
        delete window.dispararRecargaTabla;
        delete window[`reloadTable${entityName}`];

        console.log(`🗑️ Recursos de ${this.config.entityRoute} liberados`);
    }
}