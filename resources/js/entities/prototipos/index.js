import { EntityConfig } from '../../core/config/EntityConfig.js';
import { EntityManager } from '../../core/managers/EntityManager.js';
import { EntityHandler } from '../../core/handlers/EntityHandler.js';
import { formatearFecha } from '../../components/ui/dateManager.js';
import { resolveImageUrl } from '../../components/ui/imageManager.js';

/**
 * CONFIGURACIÓN PARA CARDS DE PROTOTIPOS
 */
const PROTOTIPOS_CARDS_CONFIG = EntityConfig.create({
    entityType: 'Prototipo',
    entityRoute: 'prototipos',
    urlBase: '/prototipos',
    format: 'cards',
    resultadosId: 'data-results'
});

/**
 * RENDERIZADO PARA CARDS DE PROTOTIPOS
 */
const PROTOTIPOS_CARDS_RENDER = {
    renderCard: prototipo => {
        // 🎯 RESOLVER URL DE IMAGEN (ahora para prototipos)
        let imagenUrl = resolveImageUrl(prototipo.URL_IMAGEN_PROTOTIPO, '/assets/img/default-article.png');

        // 🎯 FORMATEAR FECHA
        const fechaFormateada = formatearFecha(prototipo.FECHA_PROTOTIPO);
        
        // 🎯 PROCESAR AUTORES
        const autoresTexto = prototipo.autores && prototipo.autores.length > 0
            ? prototipo.autores.map(autor => `${autor.NOMBRE_AUTOR} ${autor.APELLIDO_AUTOR}`).join(', ')
            : 'Sin autores';

        // 🎯 PROCESAR PROPÓSITO (limitado para card)
        const propositoTexto = prototipo.PROPOSITO_PROTOTIPO && prototipo.PROPOSITO_PROTOTIPO.length > 60
            ? prototipo.PROPOSITO_PROTOTIPO.substring(0, 60) + '...'
            : prototipo.PROPOSITO_PROTOTIPO || 'Sin propósito definido';

        return `
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="/prototipos/${prototipo.ID_PROTOTIPO}" class="card-link">
                <div class="product-card">
                    <div class="product-card-img-wrapper">
                        <img src="${imagenUrl}" 
                            class="product-card-img-top" 
                            alt="Imagen del prototipo: ${prototipo.NOMBRE_PROTOTIPO}"
                            loading="lazy"
                            onerror="this.src='/assets/img/default-article.png'">
                    </div>
                    
                    <div class="product-card-body">
                        <h5 class="product-card-title">
                            ${prototipo.NOMBRE_PROTOTIPO}
                        </h5>
                        
                        <p class="product-card-date">
                            ${fechaFormateada}
                        </p>

                        <hr>
                        
                        <!-- 🎯 TABLA DE METADATOS ESPECÍFICA PARA PROTOTIPOS -->
                        <table class="metadata-table-card">
                            <tbody>
                                <tr>
                                    <td>
                                        <i class="fas fa-building me-2"></i>Institución
                                    </td>
                                    <td>
                                        ${prototipo.INSTITUCION_PROTOTIPO}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fas fa-users me-2"></i>Autores
                                    </td>
                                    <td class="authors-text">
                                        ${autoresTexto}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </a>
        </div>
        `;
    },
    containerClass: 'g-4'
};

/**
 * HANDLER PARA CARDS DE PROTOTIPOS
 */
class PrototiposCardsHandler extends EntityHandler {
    constructor() {
        super(PROTOTIPOS_CARDS_CONFIG, PROTOTIPOS_CARDS_RENDER);
    }

    // ✅ OVERRIDE: Datos específicos de prototipos (no necesarios para vista pública)
    extractButtonData(button) {
        return {};
    }

    // ✅ OVERRIDE: Datos vacíos específicos de prototipos
    getEmptyData() {
        return {};
    }

    // ✅ OVERRIDE: Configurar archivos específicos de prototipos (no necesario para vista pública)
    loadFiles(button) {
        // No se necesita para vista pública
    }

    // ✅ OVERRIDE: Procesar datos específicos de prototipos antes de renderizar
    processDataBeforeRender(data) {
        // Procesar URLs de imagen si es necesario
        if (data.prototipos && data.prototipos.data) {
            data.prototipos.data.forEach(prototipo => {
                // Ya se procesa en el controlador, pero por seguridad:
                if (prototipo.URL_IMAGEN_PROTOTIPO && !prototipo.URL_IMAGEN_PROTOTIPO.startsWith('http')) {
                    if (!prototipo.URL_IMAGEN_PROTOTIPO.startsWith('/')) {
                        prototipo.URL_IMAGEN_PROTOTIPO = '/' + prototipo.URL_IMAGEN_PROTOTIPO;
                    }
                }
            });
        }
        return data;
    }
}

/**
 * INICIALIZACIÓN
 */
const prototiposCardsHandler = new PrototiposCardsHandler();

document.addEventListener('DOMContentLoaded', async function () {
    await prototiposCardsHandler.initialize(EntityManager);
    
    // ✅ FUNCIÓN DE DEBUG específica para prototipos
    window.debugPrototiposCards = () => prototiposCardsHandler.debug();
});

/**
 * EXPORTAR PARA USO GLOBAL
 */
export { prototiposCardsHandler, PROTOTIPOS_CARDS_CONFIG, PROTOTIPOS_CARDS_RENDER };