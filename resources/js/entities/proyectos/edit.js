import { EntityConfig } from '../../core/config/EntityConfig.js';
import { EntityManager } from '../../core/managers/EntityManager.js';
import { EntityHandler } from '../../core/handlers/EntityHandler.js';
import { updateFileDisplay } from '../../components/modals/modalManager.js';

/**
 * CONFIGURACIÓN ESPECÍFICA PARA PROYECTOS
 */
const PROYECTOS_CONFIG = EntityConfig.create({
    entityType: 'Proyecto',
    entityRoute: 'proyectos',
    urlBase: '/dashboard/proyectos',
    format: 'table',
    resultadosId: 'data-results'
});

/**
 * CONFIGURACIÓN DE RENDERIZADO ESPECÍFICA PARA PROYECTOS
 */
const PROYECTOS_RENDER_CONFIG = {
    renderHeader: () => `
        <thead>
            <tr>
                <th>Título</th>
                <th>Convocatoria</th>
                <th>Organismo</th>
                <th>Ámbito</th>
                <th>Fecha</th>
                <th>Estadísticas</th>
                <th>Acciones</th>
            </tr>
        </thead>
    `,
    renderRow: proyecto => {
        // 🎯 PROCESAR AUTORES
        const autoresTexto = proyecto.autores && proyecto.autores.length > 0
            ? proyecto.autores.slice(0, 2).map(autor =>
                `${autor.NOMBRE_AUTOR} ${autor.APELLIDO_AUTOR}`
            ).join(', ') + (proyecto.autores.length > 2 ? '...' : '')
            : 'Sin investigadores';

        // 🎯 PROCESAR RESUMEN
        const resumenCorto = proyecto.RESUMEN_PROYECTO
            ? proyecto.RESUMEN_PROYECTO.substring(0, 60) + '...'
            : 'Sin descripción';

        return `
        <tr>
            <td>
                <div class="proyecto-info">
                    <h6 class="mb-1 fw-medium">${proyecto.TITULO_PROYECTO}</h6>
                    <small class="text-muted">
                        <i class="fa-solid fa-users me-1"></i>${autoresTexto}
                    </small>
                </div>
            </td>
            <td>
                <span class="badge code">${proyecto.CONVOCATORIA_PROYECTO}</span>
            </td>
            <td>
                <small class="text-muted">${proyecto.organismo?.NOMBRE_ORGANISMO || 'N/A'}</small>
            </td>
            <td>
                <small class="text-muted">${proyecto.ambito?.NOMBRE_AMBITO || 'N/A'}</small>
            </td>
            <td>${new Date(proyecto.FECHA_PROYECTO).toLocaleDateString('es-ES')}</td>
            <td>
                <div class="stats-mini">
                    <small class="d-block">
                        <i class="fa-solid fa-eye text-primary me-1"></i>${proyecto.VISTA_PROYECTO || 0}
                    </small>
                    <small class="d-block">
                        <i class="fa-solid fa-download text-success me-1"></i>${proyecto.DESCARGA_PROYECTO || 0}
                    </small>
                </div>
            </td>
            <td>
                <div class="d-flex">
                    <div class="ms-2">
                        <button type="button" class="btn custom-button custom-button-editar"
                            data-bs-toggle="modal" data-bs-target="#proyectosModal"
                            onclick="window.prepararModalProyectos()"
                            data-id="${proyecto.ID_PROYECTO}" 
                            data-titulo="${proyecto.TITULO_PROYECTO}"
                            data-resumen="${proyecto.RESUMEN_PROYECTO || ''}"
                            data-fecha="${proyecto.FECHA_PROYECTO}"
                            data-convocatoria="${proyecto.CONVOCATORIA_PROYECTO}"
                            data-organismo="${proyecto.ID_ORGANISMO}"
                            data-ambito="${proyecto.ID_AMBITO}"
                            data-url-proyecto="${proyecto.URL_PROYECTO || ''}"
                            data-url-imagen="${proyecto.URL_IMAGEN_PROYECTO || ''}"
                            data-nombres-autores="${proyecto.autores ? proyecto.autores.map(a => a.NOMBRE_AUTOR).join(',') : ''}"
                            data-apellidos-autores="${proyecto.autores ? proyecto.autores.map(a => a.APELLIDO_AUTOR).join(',') : ''}"
                            data-orden-autores="${proyecto.autores ? proyecto.autores.map(a => a.pivot?.ORDEN_AUTOR || '').join(',') : ''}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </div>
                    <div class="ms-2">
                        <button type="button" class="btn custom-button custom-button-eliminar"
                            data-bs-toggle="modal" data-bs-target="#modalEliminar"
                            data-id="${proyecto.ID_PROYECTO}" 
                            data-type="proyecto"
                            data-route="proyectos"
                            data-name="${proyecto.TITULO_PROYECTO}">
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
 * HANDLER ESPECÍFICO PARA PROYECTOS (extiende la clase base)
 */
class ProyectosHandler extends EntityHandler {
    constructor() {
        super(PROYECTOS_CONFIG, PROYECTOS_RENDER_CONFIG);
    }

    /**
     * OVERRIDE: Datos específicos de proyectos
     */
    extractButtonData(button) {
        return {
            titulo_proyectos: button.getAttribute('data-titulo') || '',
            resumen_proyectos: button.getAttribute('data-resumen') || '',
            fecha_proyectos: button.getAttribute('data-fecha') || '',
            convocatoria_proyectos: button.getAttribute('data-convocatoria') || '',
            id_organismo: button.getAttribute('data-organismo') || '',
            id_ambito: button.getAttribute('data-ambito') || ''
        };
    }

    /**
     * OVERRIDE: Datos vacíos específicos de proyectos
     */
    getEmptyData() {
        return {
            titulo_proyectos: '',
            resumen_proyectos: '',
            fecha_proyectos: '',
            convocatoria_proyectos: '',
            id_organismo: '',
            id_ambito: ''
        };
    }

    /**
     * OVERRIDE: Configurar archivos específicos de proyectos
     */
    loadFiles(button) {
        updateFileDisplay('file-proyectos', button.getAttribute('data-url-proyecto'), 'No se ha seleccionado archivo');
        updateFileDisplay('file-imagen-proyectos', button.getAttribute('data-url-imagen'), 'No se ha seleccionado imagen');
    }

    /**
     * HOOK: Eventos específicos de proyectos
     */
    configureSpecificEvents() {
        this.configurarEventosArchivos();
    }

    /**
     * ESPECÍFICO: Configurar eventos de archivos
     */
    configurarEventosArchivos() {
        const fileInputs = [
            { input: 'url_proyectos', display: 'file-proyectos', defaultMsg: 'No se ha seleccionado archivo' },
            { input: 'url_imagen_proyectos', display: 'file-imagen-proyectos', defaultMsg: 'No se ha seleccionado una imagen' }
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
     * OVERRIDE: Limpiar archivos específicos de proyectos
     */
    clearFiles() {
        updateFileDisplay('file-proyectos', '', 'No se ha seleccionado archivo');
        updateFileDisplay('file-imagen-proyectos', '', 'No se ha seleccionado imagen');
    }

    /**
     * OVERRIDE: Configurar modal específico de proyectos
     */
    configureModal() {
        // Configurar modal específico si es necesario
        const modal = document.getElementById('proyectosModal');
        if (modal) {
            console.log('Modal de proyectos configurado');
        }
    }
}

/**
 * FUNCIÓN GLOBAL PARA PREPARAR MODAL (similar a libros)
 */
window.prepararModalProyectos = function () {
    console.log('Preparando modal de proyectos...');
    // Esta función se llamará desde el botón de editar
    // El EntityHandler se encarga del resto
};

/**
 * INSTANCIA Y FUNCIONES GLOBALES
 */
const proyectosHandler = new ProyectosHandler();

/**
 * INICIALIZACIÓN SIMPLE
 */
document.addEventListener('DOMContentLoaded', async function () {
    await proyectosHandler.initialize(EntityManager);

    // ✅ FUNCIÓN DE DEBUG específica
    window.debugProyectos = () => proyectosHandler.debug();

    // ✅ Configurar eventos específicos después de inicializar
    proyectosHandler.configureModal();
});

/**
 * EXPORTAR
 */
export { proyectosHandler, PROYECTOS_CONFIG, PROYECTOS_RENDER_CONFIG };