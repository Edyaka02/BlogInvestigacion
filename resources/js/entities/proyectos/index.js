import { EntityConfig } from '../../core/config/EntityConfig.js';
import { EntityManager } from '../../core/managers/EntityManager.js';
import { EntityHandler } from '../../core/handlers/EntityHandler.js';
import { formatearFecha } from '../../components/ui/dateManager.js';
import { resolveImageUrl } from '../../components/ui/imageManager.js';

/**
 * CONFIGURACIÓN PARA CARDS DE PROYECTOS
 */
const PROYECTOS_CARDS_CONFIG = EntityConfig.create({
    entityType: 'Proyecto',
    entityRoute: 'proyectos',
    urlBase: '/proyectos',
    format: 'cards',
    resultadosId: 'data-results'
});

/**
 * RENDERIZADO PARA CARDS DE PROYECTOS
 */
const PROYECTOS_CARDS_RENDER = {
    renderCard: proyecto => {
        // 🎯 RESOLVER URL DE IMAGEN (ahora para proyectos)
        let imagenUrl = resolveImageUrl(proyecto.URL_IMAGEN_PROYECTO, '/assets/img/default-project.png');

        // 🎯 FORMATEAR FECHA
        const fechaFormateada = formatearFecha(proyecto.FECHA_PROYECTO);
        
        // 🎯 PROCESAR AUTORES
        const autoresTexto = proyecto.autores && proyecto.autores.length > 0
            ? proyecto.autores.map(autor => `${autor.NOMBRE_AUTOR} ${autor.APELLIDO_AUTOR}`).join(', ')
            : 'Sin investigadores';

        // 🎯 PROCESAR RESUMEN
        const resumenCorto = proyecto.RESUMEN_PROYECTO 
            ? proyecto.RESUMEN_PROYECTO.substring(0, 100) + '...'
            : 'Sin descripción disponible';

        return `
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="/proyectos/${proyecto.ID_PROYECTO}" class="card-link">
                <div class="product-card">
                    <div class="product-card-img-wrapper">
                        <img src="${imagenUrl}" 
                            class="product-card-img-top" 
                            alt="Imagen del proyecto: ${proyecto.TITULO_PROYECTO}"
                            loading="lazy"
                            onerror="this.src='/assets/img/default-article.png'">
                    </div>
                    
                    <div class="product-card-body">
                        <h5 class="product-card-title">
                            ${proyecto.TITULO_PROYECTO}
                        </h5>
                        
                        <p class="product-card-date">
                            ${fechaFormateada}
                        </p>

                        <hr>
                        
                        <!-- 🎯 TABLA DE METADATOS ESPECÍFICA PARA PROYECTOS -->
                        <table class="metadata-table-card">
                            <tbody>
                                <tr>
                                    <td>
                                        <i class="fas fa-bullhorn me-2"></i>Convocatoria
                                    </td>
                                    <td>
                                        <span class="badge code">${proyecto.CONVOCATORIA_PROYECTO}</span>
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
 * HANDLER PARA CARDS DE PROYECTOS
 */
class ProyectosCardsHandler extends EntityHandler {
    constructor() {
        super(PROYECTOS_CARDS_CONFIG, PROYECTOS_CARDS_RENDER);
    }

    // ✅ OVERRIDE: Datos específicos de proyectos (no necesarios para vista pública)
    extractButtonData(button) {
        return {};
    }

    // ✅ OVERRIDE: Datos vacíos específicos de proyectos
    getEmptyData() {
        return {};
    }

    // ✅ OVERRIDE: Configurar archivos específicos de proyectos (no necesario para vista pública)
    loadFiles(button) {
        // No se necesita para vista pública
    }

    // ✅ OVERRIDE: Procesar datos específicos de proyectos antes de renderizar
    processDataBeforeRender(data) {
        // Procesar URLs de imagen si es necesario
        if (data.proyectos && data.proyectos.data) {
            data.proyectos.data.forEach(proyecto => {
                // Ya se procesa en el controlador, pero por seguridad:
                if (proyecto.URL_IMAGEN_PROYECTO && !proyecto.URL_IMAGEN_PROYECTO.startsWith('http')) {
                    if (!proyecto.URL_IMAGEN_PROYECTO.startsWith('/')) {
                        proyecto.URL_IMAGEN_PROYECTO = '/' + proyecto.URL_IMAGEN_PROYECTO;
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
const proyectosCardsHandler = new ProyectosCardsHandler();

document.addEventListener('DOMContentLoaded', async function () {
    await proyectosCardsHandler.initialize(EntityManager);
    
    // ✅ FUNCIÓN DE DEBUG específica para proyectos
    window.debugProyectosCards = () => proyectosCardsHandler.debug();
});

/**
 * EXPORTAR PARA USO GLOBAL
 */
export { proyectosCardsHandler, PROYECTOS_CARDS_CONFIG, PROYECTOS_CARDS_RENDER };