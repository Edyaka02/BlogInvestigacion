import { EntityConfig } from '../../core/config/EntityConfig.js';
import { EntityManager } from '../../core/managers/EntityManager.js';
import { EntityHandler } from '../../core/handlers/EntityHandler.js';
import { updateFileDisplay } from '../../components/modals/modalManager.js';

/**
 * CONFIGURACIÓN PARA CARDS
 */
const ARTICULOS_CARDS_CONFIG = EntityConfig.create({
    entityType: 'Artículo',
    entityRoute: 'articulos',
    urlBase: '/dashboard/articulos',
    format: 'cards',
    resultadosId: 'data-results'
});

/**
 * RENDERIZADO PARA CARDS
 */
const ARTICULOS_CARDS_RENDER = {
    renderCard: articulo => `
        <div class="col-md-3 mb-3 card_busqueda">
            <div class="card custom-card">
                <div class="card-body">
                    <h5 class="card-title justify-center">${articulo.TITULO_ARTICULO}</h5>
                    <input type="hidden" name="id_evento_card" value="${articulo.ID_ARTICULO}">

                    <div class="custom-button-group">
                        <div class="btn-group mt-2" role="group" aria-label="Actions">
                            <button type="button" class="btn custom-button custom-button-editar" data-bs-toggle="modal"
                                data-bs-target="#articulosModal"
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
                                <i class="fa-solid fa-pen-to-square"></i> Editar
                            </button>

                            <button type="button" class="btn custom-button custom-button-eliminar"
                                data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                data-id="${articulo.ID_ARTICULO}" 
                                data-type="artículo"
                                data-route="articulos"
                                data-name="${articulo.TITULO_ARTICULO}">
                                <i class="fa-solid fa-trash-can"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `,
    containerClass: 'g-4'
};

/**
 * HANDLER PARA CARDS (reutiliza toda la lógica)
 */
class ArticulosCardsHandler extends EntityHandler {
    constructor() {
        super(ARTICULOS_CARDS_CONFIG, ARTICULOS_CARDS_RENDER);
    }
    
    // ✅ OVERRIDE: Datos específicos de artículos (igual que en edit.js)
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

    // ✅ OVERRIDE: Datos vacíos específicos de artículos
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

    // ✅ OVERRIDE: Configurar archivos específicos de artículos
    loadFiles(button) {
        updateFileDisplay('file-articulos', button.getAttribute('data-url-articulo'), 'No se ha seleccionado archivo');
        updateFileDisplay('file-imagen', button.getAttribute('data-url-imagen'), 'No se ha seleccionado imagen');
    }

    
}

/**
 * INICIALIZACIÓN
 */
const articulosCardsHandler = new ArticulosCardsHandler();

document.addEventListener('DOMContentLoaded', async function () {
    await articulosCardsHandler.initialize(EntityManager);
    window.debugArticulosCards = () => articulosCardsHandler.debug();
});

export { articulosCardsHandler, ARTICULOS_CARDS_CONFIG, ARTICULOS_CARDS_RENDER };