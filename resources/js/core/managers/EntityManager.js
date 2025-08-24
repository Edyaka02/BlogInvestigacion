import { loadTableResults } from '../../components/tables/tableLoader.js';

/**
 * CLASE GENÉRICA PARA MANEJAR ENTIDADES (SIMPLIFICADA)
 */
export class EntityManager {
    constructor(config) {
        this.config = config;
        this.tablaController = null;
    }

    /**
     * Función genérica para recargar tabla con AJAX
     */
    async reloadTable() {
        console.log(`🔄 Recargando tabla de ${this.config.entityRoute}...`);

        const tablaContainer = document.getElementById(this.config.resultadosId);
        const paginacionContainer = document.getElementById(this.config.paginacionId);

        if (!tablaContainer) {
            console.error('❌ Contenedor de tabla no encontrado');
            return Promise.reject('Contenedor no encontrado');
        }

        // Mostrar indicador de carga
        tablaContainer.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>';

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

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('text/html')) {
                throw new Error(`Respuesta inesperada. Content-Type: ${contentType}`);
            }

            const html = await response.text();

            if (!html.trim()) {
                throw new Error('Respuesta HTML vacía');
            }

            // Actualizar tabla
            tablaContainer.innerHTML = html;

            // Actualizar paginación si existe
            if (paginacionContainer) {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                const nuevaPaginacion = tempDiv.querySelector('.pagination');

                if (nuevaPaginacion) {
                    paginacionContainer.innerHTML = nuevaPaginacion.outerHTML;
                }
            }

            console.log(`✅ Tabla de ${this.config.entityRoute} recargada`);
            return html;

        } catch (error) {
            console.error(`❌ Error al recargar tabla de ${this.config.entityRoute}:`, error);

            const entityName = this.config.entityRoute.charAt(0).toUpperCase() + this.config.entityRoute.slice(1);
            tablaContainer.innerHTML = `
                <div class="alert alert-danger">
                    <h6>Error al cargar datos</h6>
                    <p><strong>Detalle:</strong> ${error.message}</p>
                    <button onclick="window.reloadTable${entityName}()" 
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
     * Configurar tabla con renderizado
     */
    configureTable(renderConfig) {
        if (!renderConfig) {
            console.warn('⚠️ No se proporcionó configuración de renderizado');
            return null;
        }

        this.tablaController = loadTableResults({
            urlBase: this.config.urlBase,
            buscadorFormId: this.config.searchFormId,
            filtroFormId: this.config.filtroFormId,
            resultadosDivId: this.config.resultadosId,
            paginacionDivId: this.config.paginacionId,
            key: this.config.entityRoute,
            cargarInicialmente: true,
            renderHeader: renderConfig.renderHeader,
            renderRow: renderConfig.renderRow,
            entityType: this.config.entityType,
            entityRoute: this.config.entityRoute
        });

        console.log(`✅ Tabla de ${this.config.entityRoute} configurada`);
        return this.tablaController;
    }

    /**
     * Crear función global básica de recarga
     */
    createGlobalFunctions() {
        // ✅ SOLO: Función genérica de recarga
        window.dispararRecargaTabla = () => {
            if (this.tablaController && typeof this.tablaController.recargar === 'function') {
                this.tablaController.recargar();
            } else {
                this.reloadTable();
            }
        };

        console.log(`✅ Función global base creada para ${this.config.entityRoute}`);

        return {
            recargarGenericaFunction: 'dispararRecargaTabla'
        };
    }

    /**
     * Configurar event listeners
     */
    configureEventListeners() {
        // Event listener para eventos de recarga específicos
        document.addEventListener(`reloadTable${this.config.entityRoute}`, () => {
            this.reloadTable();
        });

        // Event listener genérico
        document.addEventListener('reloadTable', (e) => {
            if (e.detail?.entityRoute === this.config.entityRoute) {
                this.reloadTable();
            }
        });

        console.log(`✅ Event listeners configurados para ${this.config.entityRoute}`);
    }

    /**
     * Inicializar sistema de tabla (SIN AJAX)
     */
    initialize(renderConfig) {
        console.log(`🚀 Inicializando sistema de tabla para ${this.config.entityRoute}...`);

        // ✅ CONFIGURAR: Tabla con renderizado
        this.configureTable(renderConfig);

        // ✅ CREAR: Función global básica
        const globalFunctions = this.createGlobalFunctions();

        // ✅ CONFIGURAR: Event listeners
        this.configureEventListeners();

        console.log(`✅ Sistema de tabla para ${this.config.entityRoute} inicializado`);

        return {
            manager: this,
            globalFunctions,
            tablaController: this.tablaController
        };
    }

    /**
     * Destruir y limpiar recursos
     */
    destroy() {
        // Limpiar función global básica
        delete window.dispararRecargaTabla;

        console.log(`🗑️ Recursos de ${this.config.entityRoute} liberados`);
    }
}