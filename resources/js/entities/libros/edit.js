import { EntityConfig } from '../../core/config/EntityConfig.js';
import { EntityManager } from '../../core/managers/EntityManager.js';
import { EntityHandler } from '../../core/handlers/EntityHandler.js';
import { updateFileDisplay } from '../../components/modals/modalManager.js';

/**
 * CONFIGURACIÓN ESPECÍFICA PARA LIBROS
 */
const LIBROS_CONFIG = EntityConfig.create({
    entityType: 'Libro',
    entityRoute: 'libros',
    urlBase: '/dashboard/libros',
    format: 'table',
    resultadosId: 'data-results'
});

/**
 * CONFIGURACIÓN DE RENDERIZADO ESPECÍFICA PARA LIBROS
 */
const LIBROS_RENDER_CONFIG = {
    renderHeader: () => `
        <thead>
            <tr>
                <th>Título</th>
                <th>ISBN</th>
                <th>Editorial</th>
                <th>Capítulo</th>
                <th>Fecha</th>
                <th>Estadísticas</th>
                <th>Acciones</th>
            </tr>
        </thead>
    `,
    renderRow: libro => {
        // 🎯 PROCESAR AUTORES
        const autoresTexto = libro.autores && libro.autores.length > 0
            ? libro.autores.slice(0, 2).map(autor =>
                `${autor.NOMBRE_AUTOR} ${autor.APELLIDO_AUTOR}`
            ).join(', ') + (libro.autores.length > 2 ? '...' : '')
            : 'Sin autores';

        // 🎯 PROCESAR CAPÍTULO
        const capituloTexto = libro.CAPITULO_LIBRO || 'N/A';

        return `
        <tr>
            <td>
                <div class="libro-info">
                    <h6 class="mb-1 fw-medium">${libro.TITULO_LIBRO}</h6>
                </div>
            </td>
            <td>
                <span class="badge code">${libro.ISBN_LIBRO}</span>
            </td>
            <td>${libro.EDITORIAL_LIBRO}</td>
            <td>
                <small class="text-muted">${capituloTexto}</small>
            </td>
            <td>${new Date(libro.FECHA_LIBRO).toLocaleDateString('es-ES')}</td>
            <td>
                <div class="stats-mini">
                    <small class="d-block">
                        <i class="fa-solid fa-eye me-1" style="color: var(--btn-azul)"></i>${libro.VISTA_LIBRO || 0}
                    </small>
                    <small class="d-block">
                        <i class="fa-solid fa-download me-1" style="color: var(--btn-verde)"></i>${libro.DESCARGA_LIBRO || 0}
                    </small>
                </div>
            </td>
            <td>
                <div class="d-flex">
                    <div class="ms-2">
                        <button type="button" class="btn custom-button custom-button-editar"
                            data-bs-toggle="modal" data-bs-target="#librosModal"
                            onclick="window.prepararModalLibros()"
                            data-id="${libro.ID_LIBRO}" 
                            data-isbn="${libro.ISBN_LIBRO}"
                            data-titulo="${libro.TITULO_LIBRO}"
                            data-capitulo="${libro.CAPITULO_LIBRO || ''}"
                            data-fecha="${libro.FECHA_LIBRO}"
                            data-editorial="${libro.EDITORIAL_LIBRO}"
                            data-doi="${libro.DOI_LIBRO || ''}"
                            data-url-libro="${libro.URL_LIBRO || ''}"
                            data-url-imagen="${libro.URL_IMAGEN_LIBRO || ''}"
                            data-nombres-autores="${libro.autores ? libro.autores.map(a => a.NOMBRE_AUTOR).join(',') : ''}"
                            data-apellidos-autores="${libro.autores ? libro.autores.map(a => a.APELLIDO_AUTOR).join(',') : ''}"
                            data-orden-autores="${libro.autores ? libro.autores.map(a => a.pivot?.ORDEN_AUTOR || '').join(',') : ''}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </div>
                    <div class="ms-2">
                        <button type="button" class="btn custom-button custom-button-eliminar"
                            data-bs-toggle="modal" data-bs-target="#modalEliminar"
                            data-id="${libro.ID_LIBRO}" 
                            data-type="libro"
                            data-route="libros"
                            data-name="${libro.TITULO_LIBRO}">
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
 * HANDLER ESPECÍFICO PARA LIBROS (extiende la clase base)
 */
class LibrosHandler extends EntityHandler {
    constructor() {
        super(LIBROS_CONFIG, LIBROS_RENDER_CONFIG);
    }

    /**
     * OVERRIDE: Datos específicos de libros
     */
    extractButtonData(button) {
        return {
            titulo_libro: button.getAttribute('data-titulo') || '',
            isbn_libro: button.getAttribute('data-isbn') || '',
            capitulo_libro: button.getAttribute('data-capitulo') || '',
            fecha_libro: button.getAttribute('data-fecha') || '',
            editorial_libro: button.getAttribute('data-editorial') || '',
            doi_libro: button.getAttribute('data-doi') || ''
        };
    }

    /**
     * OVERRIDE: Datos vacíos específicos de libros
     */
    getEmptyData() {
        return {
            titulo_libro: '',
            isbn_libro: '',
            capitulo_libro: '',
            fecha_libro: '',
            editorial_libro: '',
            doi_libro: ''
        };
    }

    /**
     * OVERRIDE: Configurar archivos específicos de libros
     */
    loadFiles(button) {
        updateFileDisplay('file-libro', button.getAttribute('data-url-libro'), 'No se ha seleccionado archivo');
        updateFileDisplay('file-imagen-libro', button.getAttribute('data-url-imagen'), 'No se ha seleccionado imagen');
    }

    /**
     * HOOK: Eventos específicos de libros
     */
    configureSpecificEvents() {
        this.configurarEventosArchivos();
    }

    /**
     * ESPECÍFICO: Configurar eventos de archivos
     */
    configurarEventosArchivos() {
        const fileInputs = [
            { input: 'url_libro', display: 'file-libro', defaultMsg: 'No se ha seleccionado archivo' },
            { input: 'url_imagen_libro', display: 'file-imagen-libro', defaultMsg: 'No se ha seleccionado una imagen' }
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
     * OVERRIDE: Limpiar archivos específicos de libros
     */
    clearFiles() {
        updateFileDisplay('file-libro', '', 'No se ha seleccionado archivo');
        updateFileDisplay('file-imagen-libro', '', 'No se ha seleccionado imagen');
    }

    /**
     * OVERRIDE: Configurar modal específico de libros
     */
    configureModal() {
        // Configurar modal específico si es necesario
        const modal = document.getElementById('librosModal');
        if (modal) {
            console.log('Modal de libros configurado');
        }
    }
}

/**
 * FUNCIÓN GLOBAL PARA PREPARAR MODAL (similar a artículos)
 */
window.prepararModalLibros = function () {
    console.log('Preparando modal de libros...');
    // Esta función se llamará desde el botón de editar
    // El EntityHandler se encarga del resto
};

/**
 * INSTANCIA Y FUNCIONES GLOBALES
 */
const librosHandler = new LibrosHandler();

/**
 * INICIALIZACIÓN SIMPLE
 */
document.addEventListener('DOMContentLoaded', async function () {
    await librosHandler.initialize(EntityManager);

    // ✅ FUNCIÓN DE DEBUG específica
    window.debugLibros = () => librosHandler.debug();

    // ✅ Configurar eventos específicos después de inicializar
    librosHandler.configureModal();
});

/**
 * EXPORTAR
 */
export { librosHandler, LIBROS_CONFIG, LIBROS_RENDER_CONFIG };