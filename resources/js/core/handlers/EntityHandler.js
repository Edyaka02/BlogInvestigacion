import {
    initializeModalAuthors,
    setModalData,
    configureFormForEdit,
    configureFormForCreate,
    updateFileDisplay,
    clearAuthorsModal,
    updateModal,
    setupFormSubmission,
    setupDeleteModal
} from '../../components/modals/modalManager.js';

import { ValidationErrorHandler } from '../../components/validations/ValidationErrorHandler.js';
import { ModalFormCleaner } from '../../components/modals/ModalFormCleaner.js';

/**
 * CLASE BASE PARA MANEJAR CUALQUIER ENTIDAD
 */
export class EntityHandler {
    constructor(config, renderConfig) {
        this.config = config;
        this.renderConfig = renderConfig;
        this.manager = null;
        this.initialized = false;
    }

    /**
     * FUNCIÓN GENÉRICA PARA CARGAR DATOS EN EL MODAL
     */
    loadModalData(button) {
        const modal = document.getElementById(this.config.modalId);
        const form = document.getElementById(this.config.modalFormId);

        if (!modal || !form) return;

        const id = button.getAttribute('data-id');
        const isEdit = !!id;

        console.log(`📝 Cargando datos para ${isEdit ? 'editar' : 'crear'} ${this.config.entityType} ID: ${id}`);

        // ✅ CONFIGURAR: Modal según sea crear o editar
        updateModal(isEdit, {
            entidad: this.config.entityType,
            botonId: 'btn_modal',
            iconoId: 'btn_modal_icon',
            textoId: 'btn_modal_text',
            tituloId: 'modalLabel'
        });

        if (isEdit) {
            this.configureEditMode(button, form, id);
        } else {
            this.configureCreateMode(form);
        }
    }

    /**
     * CONFIGURAR MODO EDICIÓN (genérico)
     */
    configureEditMode(button, form, id) {
        console.log(`📝 Cargando datos para editar ${this.config.entityType} ID: ${id}`);

        // ✅ OBTENER: Modal dentro de la función
        const modal = document.getElementById(this.config.modalId);
        if (!modal) {
            console.error(`❌ Modal no encontrado: ${this.config.modalId}`);
            return;
        }

        // Configurar formulario para edición
        configureFormForEdit(form, id, this.config.entityRoute);

        // ✅ CARGAR: Datos básicos (genérico)
        const entityData = this.extractButtonData(button);
        setModalData(modal, entityData); // ✅ Ahora modal está definido

        // ✅ CARGAR: Archivos si existen
        this.loadFiles(button);

        // ✅ CARGAR: Autores si existen
        this.loadAuthors(button);

        // ✅ HOOK: Para lógica específica de la entidad
        this.onModoEdicion?.(button, form, id);
    }

    /**
     * CONFIGURAR MODO CREACIÓN (genérico)
     */
    configureCreateMode(form) {
        console.log(`📝 Preparando modal para crear nuevo ${this.config.entityType}`);

        // ✅ OBTENER: Modal dentro de la función
        const modal = document.getElementById(this.config.modalId);
        if (!modal) {
            console.error(`❌ Modal no encontrado: ${this.config.modalId}`);
            return;
        }

        // Configurar formulario para crear
        configureFormForCreate(form, this.config.entityRoute);

        // ✅ LIMPIAR: Datos
        const emptyData = this.getEmptyData();
        setModalData(modal, emptyData); // ✅ Ahora modal está definido

        // ✅ LIMPIAR: Archivos
        this.clearFiles();

        // ✅ INICIALIZAR: Autores vacío
        this.initializeEmptyAuthors();

        // ✅ HOOK: Para lógica específica de la entidad
        this.onModoCreacion?.(form);
    }

    /**
     * EXTRAER DATOS DEL BOTÓN (genérico, override si necesitas)
     */
    extractButtonData(button) {
        // ✅ Implementación por defecto - override en clases hijas si necesitas
        const datos = {};

        // Obtener todos los data-* attributes
        for (const attr of button.attributes) {
            if (attr.name.startsWith('data-') && !attr.name.includes('autores')) {
                const key = attr.name.replace('data-', '').replace(/-/g, '_');
                datos[key] = attr.value;
            }
        }

        return datos;
    }

    /**
     * CARGAR ARCHIVOS (genérico, override si necesitas)
     */
    loadFiles(button) {
        // ✅ Por defecto, busca patrones comunes
        // const archivos = [
        //     { dataAttr: 'data-url-archivo', displayId: 'file-archivo', defaultMsg: 'No se ha seleccionado archivo' },
        //     { dataAttr: 'data-url-imagen', displayId: 'file-imagenes', defaultMsg: 'No se ha seleccionado imagen' }
        // ];

        // archivos.forEach(({ dataAttr, displayId, defaultMsg }) => {
        //     const url = button.getAttribute(dataAttr);
        //     if (document.getElementById(displayId)) {
        //         updateFileDisplay(displayId, url || '', defaultMsg);
        //     }
        // });
    }

    /**
     * CARGAR AUTORES (genérico)
     */
    loadAuthors(button) {
        const authorConfig = this.config.getAuthorConfig?.() || {
            fieldsId: `authorFields_${this.config.entityRoute}`,
            addButtonId: `addAuthor_${this.config.entityRoute}`,
            removeButtonId: `removeAuthor_${this.config.entityRoute}`
        };

        if (document.getElementById(authorConfig.fieldsId)) {
            initializeModalAuthors(
                button,
                authorConfig.fieldsId,
                authorConfig.addButtonId,
                authorConfig.removeButtonId
            );
        }
    }

    /**
     * OBTENER DATOS VACÍOS (genérico, override si necesitas)
     */
    getEmptyData() {
        // ✅ Override en clases hijas para datos específicos
        return {};
    }

    /**
     * LIMPIAR ARCHIVOS (genérico)
     */
    clearFiles() {
        ['file-archivo', 'file-imagen'].forEach(displayId => {
            if (document.getElementById(displayId)) {
                updateFileDisplay(displayId, '', 'No se ha seleccionado archivo');
            }
        });
    }

    /**
     * INICIALIZAR AUTORES VACÍOS (genérico)
     */
    initializeEmptyAuthors() {
        const authorConfig = this.config.getAuthorConfig?.() || {
            fieldsId: `authorFields_${this.config.entityRoute}`,
            addButtonId: `addAuthor_${this.config.entityRoute}`,
            removeButtonId: `removeAuthor_${this.config.entityRoute}`
        };

        console.log('🔍 Debug initializeEmptyAuthors:', authorConfig);

        const authorFieldsElement = document.getElementById(authorConfig.fieldsId);
        if (authorFieldsElement) {
            // ✅ LIMPIAR: Contenedor de autores primero
            authorFieldsElement.innerHTML = '';

            // ✅ CREAR: Botón simulado con datos vacíos pero válidos
            const buttonSimulado = document.createElement('button');
            buttonSimulado.setAttribute('data-nombres-autores', '');
            buttonSimulado.setAttribute('data-apellidos-autores', '');
            buttonSimulado.setAttribute('data-orden-autores', '');

            console.log(`🧑‍💼 Inicializando autores vacíos para ${this.config.entityType}`);

            initializeModalAuthors(
                buttonSimulado,
                authorConfig.fieldsId,
                authorConfig.addButtonId,
                authorConfig.removeButtonId
            );
        } else {
            console.warn(`⚠️ Elemento de autores no encontrado: ${authorConfig.fieldsId}`);
        }
    }

    /**
     * CONFIGURAR BOTÓN DEL MODAL (genérico)
     */
    configureModalButton() {
        const btnModal = document.getElementById('btn_modal');
        const form = document.getElementById(this.config.modalFormId);

        if (btnModal && form) {
            const newBtn = btnModal.cloneNode(true);
            btnModal.parentNode.replaceChild(newBtn, btnModal);

            newBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                console.log(`🔘 Botón del modal clickeado, enviando formulario de ${this.config.entityType}...`);

                const submitEvent = new Event('submit', {
                    bubbles: true,
                    cancelable: true
                });

                form.dispatchEvent(submitEvent);
            }.bind(this));

            console.log(`✅ Botón del modal configurado para ${this.config.entityType}`);
        }
    }

    /**
     * CONFIGURAR EVENTOS DEL MODAL (genérico)
     */
    configureModalEvents() {
        const modal = document.getElementById(this.config.modalId);
        if (!modal) return;

        modal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget;
            if (button) this.loadModalData(button);
        });

        modal.addEventListener('shown.bs.modal', () => {
            setTimeout(() => this.configureModalButton(), 100);
        });

        modal.addEventListener('hidden.bs.modal', () => {
            // ✅ LIMPIAR: Validaciones exhaustivamente
            ValidationErrorHandler.clear();
            ModalFormCleaner.clearAll(this.config.modalId);
            this.clearModal();
        });
    }

    /**
     * LIMPIAR MODAL (genérico, override si necesitas)
     */
    clearModal() {
        const authorConfig = this.config.getAuthorConfig?.() || {
            fieldsId: `authorFields_${this.config.entityRoute}`
        };

        if (document.getElementById(authorConfig.fieldsId)) {
            clearAuthorsModal(authorConfig.fieldsId);
        }

        updateModal(false, { entidad: this.config.entityType });

        // ✅ HOOK: Para lógica específica de limpieza
        this.onClearModal?.();
    }

    /**
     * FUNCIÓN GENÉRICA PARA RECARGAR DATOS (actualizada)
     */
    reloadData() {
        console.log(`🔄 Recargando ${this.config.format} de ${this.config.entityRoute}...`);

        if (this.manager?.dataController?.recargar) {
            this.manager.dataController.recargar();
        } else if (this.manager?.reloadData) {
            this.manager.reloadData().catch(() => window.location.reload());
        } else {
            window.location.reload();
        }
    }

    /**
     * MANTENER: Compatibilidad con reloadTable
     */
    reloadTable() {
        this.reloadData();
    }

    /**
     * CONFIGURAR AJAX (genérico)
     */
    configureAjax() {
        setupFormSubmission(
            this.config.modalFormId,
            this.config.modalId,
            this.config.entityType,
            this.config.entityRoute
        );

        setupDeleteModal(
            this.config.deleteModalId,
            this.config.deleteFormId
        );

        console.log(`✅ AJAX configurado para ${this.config.entityType}`);
    }

    /**
     * CONFIGURAR FUNCIONES GLOBALES (actualizado)
     */
    configureGlobalFunctions() {
        const entityName = this.config.entityRoute.charAt(0).toUpperCase() + this.config.entityRoute.slice(1);

        // ✅ FUNCIÓN: Principal genérica
        window[`reloadData${entityName}`] = () => this.reloadData();
        
        // ✅ MANTENER: Compatibilidad
        window[`reloadTable${entityName}`] = () => this.reloadData();
        window[`prepararModal${entityName}`] = () => console.log(`🔧 Preparando modal de ${this.config.entityType}...`);
        window.dispararRecargaTabla = () => this.reloadData();
        window[`${this.config.entityRoute}Manager`] = this.manager;

        console.log(`✅ Funciones globales configuradas para ${this.config.entityType}`);
    }

    /**
     * INICIALIZAR SISTEMA COMPLETO (genérico)
     */
    async initialize(EntityManager) {
        if (this.initialized) {
            console.warn(`⚠️ ${this.config.entityType} ya está inicializado`);
            return;
        }

        console.log(`🚀 Inicializando sistema de ${this.config.entityType}...`);

        // ✅ CREAR: Manager
        this.manager = new EntityManager(this.config);

        // ✅ INICIALIZAR: Sistema completo
        const result = this.manager.initialize(this.renderConfig);

        // ✅ CONFIGURAR: AJAX
        this.configureAjax();

        // ✅ CONFIGURAR: Eventos del modal
        this.configureModalEvents();

        // ✅ CONFIGURAR: Funciones globales
        this.configureGlobalFunctions();

        // ✅ CONFIGURAR: Eventos específicos (hook)
        this.configureSpecificEvents?.();

        // ✅ HOOK: Para lógica específica de inicialización
        this.onInitialize?.(result);

        this.initialized = true;
        console.log(`🎯 Sistema de ${this.config.entityType} listo`);

        return result;
    }

    /**
     * FUNCIÓN DE DEBUG (genérica)
     */
    debug() {
        console.log(`🔍 Debug sistema ${this.config.entityType}:`, {
            config: this.config,
            manager: this.manager,
            initialized: this.initialized,
            elementos: {
                modal: !!document.getElementById(this.config.modalId),
                form: !!document.getElementById(this.config.modalFormId),
                deleteModal: !!document.getElementById(this.config.deleteModalId)
            }
        });
    }
}