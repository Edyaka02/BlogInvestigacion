import { cargarResultadosTabla } from './cargarTabla.js';
// import { setupDeleteModal, setupFormSubmission } from './modal.js';
import { setupDeleteModal, setupFormSubmission } from './modalManager.js';

/**
 * CLASE GENÉRICA PARA MANEJAR ENTIDADES
 */
export class EntityManager {
    constructor(config) {
        this.config = config;
        this.tablaController = null;
        this.modalHandlersCargados = false;
        this.ajaxConfigurado = false;
    }

    /**
     * Función genérica para recargar tabla con AJAX
     */
    async recargarTabla() {
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

        // Agregar datos del formulario de búsqueda
        if (searchForm) {
            const searchData = new FormData(searchForm);
            for (let [key, value] of searchData.entries()) {
                formData.append(key, value);
            }
        }

        // Agregar datos del formulario de filtros
        if (filtroForm) {
            const filtroData = new FormData(filtroForm);
            for (let [key, value] of filtroData.entries()) {
                formData.append(key, value);
            }
        }

        // try {
        //     const response = await fetch(this.config.urlBase, {
        //         method: 'POST',
        //         body: formData,
        //         headers: {
        //             'X-Requested-With': 'XMLHttpRequest',
        //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        //             'Accept': 'text/html' // ✅ AGREGAR: Especificar que esperamos HTML
        //         }
        //     });

        try {
            const response = await fetch(this.config.urlBase, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'text/html',
                    // 'Cache-Control': 'no-cache, no-store, must-revalidate', // ✅ AGREGAR
                    // 'Pragma': 'no-cache', // ✅ AGREGAR
                    // 'Expires': '0' // ✅ AGREGAR
                }
            });

            if (!response.ok) {
                throw new Error(`Error ${response.status}`);
            }

            // ✅ VERIFICAR: Content-Type antes de procesar
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('text/html')) {
                throw new Error(`Respuesta inesperada. Content-Type: ${contentType}`);
            }

            const html = await response.text();

            // ✅ VERIFICAR: Que el HTML no esté vacío
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
                    <button onclick="window.recargarTabla${entityName}()" 
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
     * Función genérica para cargar modal handlers
     */
    async cargarModalHandlers() {
        // ✅ VERIFICAR: Si existen los paths antes de intentar cargar
        if (!this.modalHandlersCargados && this.config.modalHandlerPath && this.config.modalHandlerFunction) {
            console.log(`🔄 Cargando modal handlers para ${this.config.entityRoute}...`);
            try {
                const module = await import(/* @vite-ignore */ this.config.modalHandlerPath);
                const initFunction = module[this.config.modalHandlerFunction];
                if (initFunction) {
                    initFunction();
                    this.modalHandlersCargados = true;
                    console.log(`✅ Modal handlers de ${this.config.entityRoute} cargados exitosamente`);
                } else {
                    throw new Error(`Función ${this.config.modalHandlerFunction} no encontrada`);
                }
            } catch (error) {
                console.error(`❌ Error al cargar modal handlers de ${this.config.entityRoute}:`, error);
                throw error;
            }
        } else if (!this.config.modalHandlerPath) {
            // ✅ NO HAY HANDLERS EXTERNOS: Solo logear que no se necesitan
            console.log(`ℹ️ ${this.config.entityRoute} no necesita handlers externos`);
            this.modalHandlersCargados = true; // Marcar como "cargado" para evitar intentos futuros
        }
    }

    /**
     * Configurar tabla con renderizado
     */
    configurarTabla(renderConfig) {
        if (!renderConfig) {
            console.warn('⚠️ No se proporcionó configuración de renderizado');
            return null;
        }

        this.tablaController = cargarResultadosTabla({
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
     * Configurar AJAX para CRUD
     */
    // configurarAjax() {
    //     let configured = false;

    //     // Configurar formulario para AJAX (crear/editar)
    //     if (this.config.modalFormId && this.config.modalId) {
    //         const form = document.getElementById(this.config.modalFormId);
    //         const modal = document.getElementById(this.config.modalId);

    //         if (form && modal) {
    //             setupFormSubmission(this.config.modalFormId, this.config.modalId, this.config.entityType, this.config.entityRoute);
    //             console.log(`✅ Formulario de ${this.config.entityRoute} configurado para AJAX`);
    //             configured = true;
    //         }
    //     }

    //     // Configurar modal eliminar
    //     if (this.config.deleteModalId && this.config.deleteFormId) {
    //         const deleteModal = document.getElementById(this.config.deleteModalId);
    //         const deleteForm = document.getElementById(this.config.deleteFormId);

    //         if (deleteModal && deleteForm) {
    //             setupDeleteModal(this.config.deleteModalId, this.config.deleteFormId);
    //             console.log(`✅ Modal eliminar de ${this.config.entityRoute} configurado`);
    //             configured = true;
    //         }
    //     }

    //     if (!configured) {
    //         console.warn(`⚠️ No se pudieron configurar modales AJAX para ${this.config.entityRoute}`);
    //     }
    // }

        /**
     * Configurar AJAX para CRUD (MEJORADO)
     */
    configurarAjax() {
        // ✅ VERIFICAR: Que no se configure múltiples veces
        if (this.ajaxConfigurado) {
            console.log(`⚠️ AJAX ya configurado para ${this.config.entityRoute}`);
            return;
        }

        let configured = false;

        // ✅ CONFIGURAR: Formulario para AJAX (crear/editar)
        if (this.config.modalFormId && this.config.modalId) {
            // ✅ USAR: setTimeout para asegurar que el DOM está listo
            setTimeout(() => {
                const form = document.getElementById(this.config.modalFormId);
                const modal = document.getElementById(this.config.modalId);

                if (form && modal) {
                    setupFormSubmission(
                        this.config.modalFormId, 
                        this.config.modalId, 
                        this.config.entityType, 
                        this.config.entityRoute
                    );
                    console.log(`✅ Formulario AJAX configurado para ${this.config.entityRoute}`);
                    configured = true;
                } else {
                    console.warn(`⚠️ Elementos del formulario no encontrados para ${this.config.entityRoute}:`, {
                        form: !!form,
                        modal: !!modal,
                        formId: this.config.modalFormId,
                        modalId: this.config.modalId
                    });
                }
            }, 100); // ✅ DELAY: Para asegurar que el DOM está listo
        }

        // ✅ CONFIGURAR: Modal eliminar
        if (this.config.deleteModalId && this.config.deleteFormId) {
            setTimeout(() => {
                const deleteModal = document.getElementById(this.config.deleteModalId);
                const deleteForm = document.getElementById(this.config.deleteFormId);

                if (deleteModal && deleteForm) {
                    setupDeleteModal(this.config.deleteModalId, this.config.deleteFormId);
                    console.log(`✅ Modal eliminar configurado para ${this.config.entityRoute}`);
                    configured = true;
                } else {
                    console.warn(`⚠️ Elementos del modal eliminar no encontrados para ${this.config.entityRoute}:`, {
                        deleteModal: !!deleteModal,
                        deleteForm: !!deleteForm,
                        deleteModalId: this.config.deleteModalId,
                        deleteFormId: this.config.deleteFormId
                    });
                }
            }, 100);
        }

        // ✅ MARCAR: Como configurado para evitar duplicados
        this.ajaxConfigurado = true;

        if (!configured) {
            console.warn(`⚠️ No se configuró AJAX para ${this.config.entityRoute} - Revisa la configuración`);
        }

        return configured;
    }

    /**
     * Crear funciones globales específicas de la entidad
     */
    crearFuncionesGlobales() {
        const entityName = this.config.entityRoute.charAt(0).toUpperCase() + this.config.entityRoute.slice(1);

        // Función global para recargar tabla específica
        const recargarFunctionName = `recargarTabla${entityName}`;
        window[recargarFunctionName] = () => this.recargarTabla();

        // Función global para preparar modal específico
        const prepararModalFunctionName = `prepararModal${entityName}`;
        window[prepararModalFunctionName] = () => this.cargarModalHandlers();

        // Función genérica de recarga
        window.dispararRecargaTabla = () => this.recargarTabla();

        console.log(`✅ Funciones globales creadas: ${recargarFunctionName}, ${prepararModalFunctionName}`);

        return {
            recargarFunction: recargarFunctionName,
            prepararModalFunction: prepararModalFunctionName
        };
    }

    /**
     * Configurar event listeners
     */
    configurarEventListeners() {
        // Event listener para eventos de recarga específicos
        document.addEventListener(`recargarTabla${this.config.entityRoute}`, () => {
            this.recargarTabla();
        });

        // Event listener genérico
        document.addEventListener('recargarTabla', (e) => {
            if (e.detail?.entityRoute === this.config.entityRoute) {
                this.recargarTabla();
            }
        });

        console.log(`✅ Event listeners configurados para ${this.config.entityRoute}`);
    }

    /**
     * Inicializar todo el sistema de la entidad
     */
    inicializar(renderConfig) {
        console.log(`🚀 Inicializando sistema de ${this.config.entityRoute}...`);

        // Configurar tabla con renderizado
        this.configurarTabla(renderConfig);

        // Configurar AJAX
        this.configurarAjax();

        // Crear funciones globales
        const globalFunctions = this.crearFuncionesGlobales();

        // Configurar event listeners
        this.configurarEventListeners();

        console.log(`✅ Sistema de ${this.config.entityRoute} inicializado completamente`);

        return {
            manager: this,
            globalFunctions,
            tablaController: this.tablaController
        };
    }

    // ✅ AGREGAR: Método para configurar AJAX manualmente (opcional)
    configurarAjaxManual() {
        console.log(`🔧 Configurando AJAX manualmente para ${this.config.entityRoute}...`);
        this.ajaxConfigurado = false; // Reset flag
        return this.configurarAjax();
    }

    /**
     * Destruir y limpiar recursos
     */
    destruir() {
        const entityName = this.config.entityRoute.charAt(0).toUpperCase() + this.config.entityRoute.slice(1);

        // Limpiar funciones globales
        delete window[`recargarTabla${entityName}`];
        delete window[`prepararModal${entityName}`];

        console.log(`🗑️ Recursos de ${this.config.entityRoute} liberados`);
    }
}