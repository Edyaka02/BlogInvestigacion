import { EntityConfig } from '../../core/config/EntityConfig.js';
import { EntityManager } from '../../core/managers/EntityManager.js';
import { EntityHandler } from '../../core/handlers/EntityHandler.js';
import { updateFileDisplay } from '../../components/modals/modalManager.js';

/**
 * CONFIGURACIÓN ESPECÍFICA PARA ARTÍCULOS
 */
const ARTICULOS_CONFIG = EntityConfig.create({
    entityType: 'Artículo',
    entityRoute: 'articulos',
    urlBase: '/dashboard/articulos',
    format: 'table',
    resultadosId: 'data-results'
});

/**
 * CONFIGURACIÓN DE RENDERIZADO ESPECÍFICA PARA ARTÍCULOS
 */
const ARTICULOS_RENDER_CONFIG = {
    renderHeader: () => `
        <thead>
            <tr>
                <th>Título</th>
                <th>ISSN</th>
                <th>Fecha</th>
                <th>Revista</th>
                <th>Tipo</th>
                <th>Acciones</th>
            </tr>
        </thead>
    `,
    renderRow: articulo => `
        <tr>
            <td>
                <div class="articulo-info">
                    <h6 class="mb-1 fw-medium">${articulo.TITULO_ARTICULO}</h6>
                </div>
            </td>
            <td>
                <span class="badge code">${articulo.ISSN_ARTICULO}</span>
            </td>
            <td>
                ${new Date(articulo.FECHA_ARTICULO).toLocaleDateString('es-ES')}
            </td>
            <td>
                <small class="text-muted">${articulo.REVISTA_ARTICULO || 'N/A'}</small>
            </td>
            <td>
                <small class="text-muted">${articulo.tipo ? articulo.tipo.NOMBRE_TIPO : ''}</small>
            </td>
            <td>
                <div class="d-flex">
                    <div class="ms-2">
                        <button type="button" class="btn custom-button custom-button-editar"
                            data-bs-toggle="modal" data-bs-target="#articulosModal"
                            onclick="window.prepararModalArticulos()"
                            data-id="${articulo.ID_ARTICULO}" 
                            data-issn="${articulo.ISSN_ARTICULO}"
                            data-titulo="${articulo.TITULO_ARTICULO}"
                            data-resumen="${articulo.RESUMEN_ARTICULO || ''}"
                            data-fecha="${articulo.FECHA_ARTICULO}"
                            data-revista="${articulo.REVISTA_ARTICULO}"
                            data-tipo="${articulo.ID_TIPO || ''}"
                            data-url-revista="${articulo.URL_REVISTA_ARTICULO || ''}"
                            data-url-articulo="${articulo.URL_ARTICULO || ''}"
                            data-url-imagen="${articulo.URL_IMAGEN_ARTICULO || ''}"
                            data-nombres-autores="${articulo.autores ? articulo.autores.map(a => a.NOMBRE_AUTOR).join(',') : ''}"
                            data-apellidos-autores="${articulo.autores ? articulo.autores.map(a => a.APELLIDO_AUTOR).join(',') : ''}"
                            data-orden-autores="${articulo.autores ? articulo.autores.map(a => a.pivot?.ORDEN_AUTOR || '').join(',') : ''}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </div>
                    <div class="ms-2">
                        <button type="button" class="btn custom-button custom-button-eliminar"
                            data-bs-toggle="modal" data-bs-target="#modalEliminar"
                            data-id="${articulo.ID_ARTICULO}" 
                            data-type="artículo"
                            data-route="articulos"
                            data-name="${articulo.TITULO_ARTICULO}">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </td>
        </tr>
    `
};

/**
 * HANDLER ESPECÍFICO PARA ARTÍCULOS (extiende la clase base)
 */
class ArticulosHandler extends EntityHandler {
    constructor() {
        super(ARTICULOS_CONFIG, ARTICULOS_RENDER_CONFIG);
    }

    /**
     * OVERRIDE: Datos específicos de artículos
     */
    extractButtonData(button) {
        return {
            titulo_articulos: button.getAttribute('data-titulo') || '',
            issn_articulos: button.getAttribute('data-issn') || '',
            resumen_articulos: button.getAttribute('data-resumen') || '',
            fecha_articulos: button.getAttribute('data-fecha') || '',
            revista_articulos: button.getAttribute('data-revista') || '',
            id_tipo: button.getAttribute('data-tipo') || '',
            url_revista_articulos: button.getAttribute('data-url-revista') || ''
        };
    }

    /**
     * OVERRIDE: Datos vacíos específicos de artículos
     */
    getEmptyData() {
        return {
            titulo_articulos: '',
            issn_articulos: '',
            resumen_articulos: '',
            fecha_articulos: '',
            revista_articulos: '',
            id_tipo: '',
            url_revista_articulos: ''
        };
    }

    /**
     * OVERRIDE: Configurar archivos específicos de artículos
     */
    loadFiles(button) {
        updateFileDisplay('file-articulos', button.getAttribute('data-url-articulo'), 'No se ha seleccionado archivo');
        updateFileDisplay('file-imagen', button.getAttribute('data-url-imagen'), 'No se ha seleccionado imagen');
    }

    /**
     * HOOK: Eventos específicos de artículos
     */
    configureSpecificEvents() {
        this.configurarEventosArchivos();
    }

    /**
     * ESPECÍFICO: Configurar eventos de archivos
     */
    configurarEventosArchivos() {
        const fileInputs = [
            { input: 'url_articulos', display: 'file-articulos', defaultMsg: 'No se ha seleccionado archivo' },
            { input: 'url_imagen_articulos', display: 'file-imagen', defaultMsg: 'No se ha seleccionado una imagen' }
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

    clearFiles() {
        updateFileDisplay('file-articulos', '', 'No se ha seleccionado archivo');
        updateFileDisplay('file-imagen', '', 'No se ha seleccionado imagen');
    }
}

/**
 * INSTANCIA Y FUNCIONES GLOBALES
 */
const articulosHandler = new ArticulosHandler();

/**
 * INICIALIZACIÓN SIMPLE
 */
document.addEventListener('DOMContentLoaded', async function () {
    await articulosHandler.initialize(EntityManager);

    // ✅ FUNCIÓN DE DEBUG específica
    window.debugArticulos = () => articulosHandler.debug();
});

/**
 * EXPORTAR
 */
export { articulosHandler, ARTICULOS_CONFIG, ARTICULOS_RENDER_CONFIG };