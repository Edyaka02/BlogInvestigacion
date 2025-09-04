import { EntityConfig } from '../../core/config/EntityConfig.js';
import { EntityManager } from '../../core/managers/EntityManager.js';
import { EntityHandler } from '../../core/handlers/EntityHandler.js';
import { formatearFecha } from '../../components/ui/dateManager.js';
import { resolveImageUrl } from '../../components/ui/imageManager.js';
/**
 * CONFIGURACIÓN PARA CARDS
 */
const ARTICULOS_CARDS_CONFIG = EntityConfig.create({
    entityType: 'Artículo',
    entityRoute: 'articulos',
    urlBase: '/articulos',
    format: 'cards',
    resultadosId: 'data-results'
});

/**
 * RENDERIZADO PARA CARDS
 */
const ARTICULOS_CARDS_RENDER = {
    renderCard: articulo => {
        let imagenUrl = resolveImageUrl(articulo.URL_IMAGEN_ARTICULO, '/assets/img/default-article.png');

        const fechaFormateada = formatearFecha(articulo.FECHA_ARTICULO);
        const autoresTexto = articulo.autores && articulo.autores.length > 0
            ? articulo.autores.map(autor => `${autor.NOMBRE_AUTOR} ${autor.APELLIDO_AUTOR}`).join(', ')
            : 'Sin autores';

        return `
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="/articulos/${articulo.ID_ARTICULO}" class="card-link">
                <div class="product-card">
                    <div class="product-card-img-wrapper">
                        <img src="${imagenUrl}" 
                            class="product-card-img-top" 
                            alt="Imagen del artículo: ${articulo.TITULO_ARTICULO}"
                            loading="lazy"
                            onerror="this.src='/assets/img/default-article.png'">
                    </div>
                    
                    <div class="product-card-body">
                        <h5 class="product-card-title">
                            ${articulo.TITULO_ARTICULO}
                        </h5>
                        
                        <p class="product-card-date">
                            ${fechaFormateada}
                        </p>

                        <hr>
                        
                        <!-- 🎯 TABLA COMO EN SHOW -->
                        <table class="metadata-table-card">
                            <tbody>
                                <tr>
                                    <td>
                                        <i class="fas fa-barcode me-2"></i>ISSN
                                    </td>
                                    <td>
                                        <code class="code">${articulo.ISSN_ARTICULO}</code>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fas fa-user me-2"></i>Autores
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
 * UTILIDAD: Formatear fecha como en Blade
 */
// function formatearFecha(fechaString) {
//     if (!fechaString) return 'Fecha no disponible';

//     try {
//         const fecha = new Date(fechaString);

//         // ✅ DÍAS: En español
//         const diasSemana = [
//             'domingo', 'lunes', 'martes', 'miércoles',
//             'jueves', 'viernes', 'sábado'
//         ];

//         // ✅ MESES: En español
//         const meses = [
//             'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
//             'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
//         ];

//         const diaSemana = diasSemana[fecha.getDay()];
//         const dia = fecha.getDate();
//         const mes = meses[fecha.getMonth()];
//         const año = fecha.getFullYear();

//         // ✅ FORMATO: "lunes, 15 de marzo de 2024"
//         return `${diaSemana}, ${dia} de ${mes} de ${año}`;

//     } catch (error) {
//         console.error('Error al formatear fecha:', error);
//         return fechaString; // Devolver original si hay error
//     }
// }

/**
 * HANDLER PARA CARDS (reutiliza toda la lógica)
 */
class ArticulosCardsHandler extends EntityHandler {
    constructor() {
        super(ARTICULOS_CARDS_CONFIG, ARTICULOS_CARDS_RENDER);
    }

    // ✅ OVERRIDE: Datos específicos de artículos (igual que en edit.js)
    extractButtonData(button) {
        return {};
    }

    // ✅ OVERRIDE: Datos vacíos específicos de artículos
    getEmptyData() {
        return {};
    }

    // ✅ OVERRIDE: Configurar archivos específicos de artículos
    loadFiles(button) {

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