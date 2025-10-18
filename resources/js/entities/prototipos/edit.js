import { EntityConfig } from '../../core/config/EntityConfig.js';
import { EntityManager } from '../../core/managers/EntityManager.js';
import { EntityHandler } from '../../core/handlers/EntityHandler.js';
import { updateFileDisplay } from '../../components/modals/modalManager.js';

/**
 * CONFIGURACIÓN ESPECÍFICA PARA PROTOTIPOS
 */
const PROTOTIPOS_CONFIG = EntityConfig.create({
    entityType: 'Prototipo',
    entityRoute: 'prototipos',
    urlBase: '/dashboard/prototipos',
    format: 'table',
    resultadosId: 'data-results'
});

/**
 * CONFIGURACIÓN DE RENDERIZADO ESPECÍFICA PARA PROTOTIPOS
 */
const PROTOTIPOS_RENDER_CONFIG = {
    renderHeader: () => `
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Propósito</th>
                <th>Institución</th>
                <th>Fecha</th>
                <th>Estadísticas</th>
                <th>Acciones</th>
            </tr>
        </thead>
    `,
    renderRow: prototipo => {
        // 🎯 PROCESAR AUTORES
        const autoresTexto = prototipo.autores && prototipo.autores.length > 0
            ? prototipo.autores.slice(0, 2).map(autor =>
                `${autor.NOMBRE_AUTOR} ${autor.APELLIDO_AUTOR}`
            ).join(', ') + (prototipo.autores.length > 2 ? '...' : '')
            : 'Sin autores';

        // 🎯 PROCESAR PROPÓSITO (limitado para tabla)
        const propositoTexto = prototipo.PROPOSITO_PROTOTIPO && prototipo.PROPOSITO_PROTOTIPO.length > 50
            ? prototipo.PROPOSITO_PROTOTIPO.substring(0, 50) + '...'
            : prototipo.PROPOSITO_PROTOTIPO || 'N/A';

        return `
        <tr>
            <td>
                <div class="prototipo-info">
                    <h6 class="mb-1 fw-medium">${prototipo.NOMBRE_PROTOTIPO}</h6>
                    <small class="text-muted">${autoresTexto}</small>
                </div>
            </td>
            <td>
                <small class="text-muted">${propositoTexto}</small>
            </td>
            <td>${prototipo.INSTITUCION_PROTOTIPO}</td>
            <td>${new Date(prototipo.FECHA_PROTOTIPO).toLocaleDateString('es-ES')}</td>
            <td>
                <div class="stats-mini">
                    <small class="d-block">
                        <i class="fa-solid fa-eye me-1" style="color: var(--btn-azul)"></i>${prototipo.VISTA_PROTOTIPO || 0}
                    </small>
                    <small class="d-block">
                        <i class="fa-solid fa-download me-1" style="color: var(--btn-verde)"></i>${prototipo.DESCARGA_PROTOTIPO || 0}
                    </small>
                </div>
            </td>
            <td>
                <div class="d-flex">
                    <div class="ms-2">
                        <button type="button" class="btn custom-button custom-button-editar"
                            data-bs-toggle="modal" data-bs-target="#prototiposModal"
                            onclick="window.prepararModalPrototipos()"
                            data-id="${prototipo.ID_PROTOTIPO}" 
                            data-nombre="${prototipo.NOMBRE_PROTOTIPO}"
                            data-proposito="${prototipo.PROPOSITO_PROTOTIPO}"
                            data-institucion="${prototipo.INSTITUCION_PROTOTIPO}"
                            data-descripcion="${prototipo.DESCRIPCION_PROTOTIPO || ''}"
                            data-objetivo="${prototipo.OBJETIVO_PROTOTIPO || ''}"
                            data-caracteristicas="${prototipo.CARACTERISTICAS_PROTOTIPO || ''}"
                            data-fecha="${prototipo.FECHA_PROTOTIPO}"
                            data-url-prototipo="${prototipo.URL_PROTOTIPO || ''}"
                            data-url-imagen="${prototipo.URL_IMAGEN_PROTOTIPO || ''}"
                            data-nombres-autores="${prototipo.autores ? prototipo.autores.map(a => a.NOMBRE_AUTOR).join(',') : ''}"
                            data-apellidos-autores="${prototipo.autores ? prototipo.autores.map(a => a.APELLIDO_AUTOR).join(',') : ''}"
                            data-orden-autores="${prototipo.autores ? prototipo.autores.map(a => a.pivot?.ORDEN_AUTOR || '').join(',') : ''}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </div>
                    <div class="ms-2">
                        <button type="button" class="btn custom-button custom-button-eliminar"
                            data-bs-toggle="modal" data-bs-target="#modalEliminar"
                            data-id="${prototipo.ID_PROTOTIPO}" 
                            data-type="prototipo"
                            data-route="prototipos"
                            data-name="${prototipo.NOMBRE_PROTOTIPO}">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </td>
        </tr>
    `
    }
};

/**
 * HANDLER ESPECÍFICO PARA PROTOTIPOS (extiende la clase base)
 */
class PrototiposHandler extends EntityHandler {
    constructor() {
        super(PROTOTIPOS_CONFIG, PROTOTIPOS_RENDER_CONFIG);
    }

    /**
     * OVERRIDE: Datos específicos de prototipos
     */
    extractButtonData(button) {
        return {
            nombre_prototipo: button.getAttribute('data-nombre') || '',
            proposito_prototipo: button.getAttribute('data-proposito') || '',
            institucion_prototipo: button.getAttribute('data-institucion') || '',
            descripcion_prototipo: button.getAttribute('data-descripcion') || '',
            objetivo_prototipo: button.getAttribute('data-objetivo') || '',
            caracteristicas_prototipo: button.getAttribute('data-caracteristicas') || '',
            fecha_prototipo: button.getAttribute('data-fecha') || ''
        };
    }

    /**
     * OVERRIDE: Datos vacíos específicos de prototipos
     */
    getEmptyData() {
        return {
            nombre_prototipo: '',
            proposito_prototipo: '',
            institucion_prototipo: '',
            descripcion_prototipo: '',
            objetivo_prototipo: '',
            caracteristicas_prototipo: '',
            fecha_prototipo: ''
        };
    }

    /**
     * OVERRIDE: Configurar archivos específicos de prototipos
     */
    loadFiles(button) {
        updateFileDisplay('file-prototipo', button.getAttribute('data-url-prototipo'), 'No se ha seleccionado archivo');
        updateFileDisplay('file-imagen-prototipo', button.getAttribute('data-url-imagen'), 'No se ha seleccionado imagen');
    }

    /**
     * HOOK: Eventos específicos de prototipos
     */
    configureSpecificEvents() {
        this.configurarEventosArchivos();
    }

    /**
     * ESPECÍFICO: Configurar eventos de archivos
     */
    configurarEventosArchivos() {
        const fileInputs = [
            { input: 'url_prototipo', display: 'file-prototipo', defaultMsg: 'No se ha seleccionado archivo' },
            { input: 'url_imagen_prototipo', display: 'file-imagen-prototipo', defaultMsg: 'No se ha seleccionado una imagen' }
        ];

        fileInputs.forEach(({ input, display, defaultMsg }) => {
            const inputElement = document.getElementById(input);
            if (inputElement) {
                inputElement.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        updateFileDisplay(display, file.name, defaultMsg);
                    }
                });
            }
        });
    }

    /**
     * OVERRIDE: Limpiar archivos específicos de prototipos
     */
    clearFiles() {
        updateFileDisplay('file-prototipo', '', 'No se ha seleccionado archivo');
        updateFileDisplay('file-imagen-prototipo', '', 'No se ha seleccionado imagen');
    }

    /**
     * OVERRIDE: Configurar modal específico de prototipos
     */
    configureModal() {
        // Configurar modal específico si es necesario
        const modal = document.getElementById('prototiposModal');
        if (modal) {
            console.log('Modal de prototipos configurado');
        }
    }
}

/**
 * FUNCIÓN GLOBAL PARA PREPARAR MODAL (similar a libros)
 */
window.prepararModalPrototipos = function () {
    console.log('Preparando modal de prototipos...');
    // Esta función se llamará desde el botón de editar
    // El EntityHandler se encarga del resto
};

/**
 * INSTANCIA Y FUNCIONES GLOBALES
 */
const prototiposHandler = new PrototiposHandler();

/**
 * INICIALIZACIÓN SIMPLE
 */
document.addEventListener('DOMContentLoaded', async function () {
    await prototiposHandler.initialize(EntityManager);

    // ✅ FUNCIÓN DE DEBUG específica
    window.debugPrototipos = () => prototiposHandler.debug();

    // ✅ Configurar eventos específicos después de inicializar
    prototiposHandler.configureModal();
});

/**
 * EXPORTAR
 */
export { prototiposHandler, PROTOTIPOS_CONFIG, PROTOTIPOS_RENDER_CONFIG };