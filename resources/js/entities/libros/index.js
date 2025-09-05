import { EntityConfig } from '../../core/config/EntityConfig.js';
import { EntityManager } from '../../core/managers/EntityManager.js';
import { EntityHandler } from '../../core/handlers/EntityHandler.js';
import { formatearFecha } from '../../components/ui/dateManager.js';
import { resolveImageUrl } from '../../components/ui/imageManager.js';

/**
 * CONFIGURACIÓN PARA CARDS DE LIBROS
 */
const LIBROS_CARDS_CONFIG = EntityConfig.create({
    entityType: 'Libro',
    entityRoute: 'libros',
    urlBase: '/libros',
    format: 'cards',
    resultadosId: 'data-results'
});

/**
 * RENDERIZADO PARA CARDS DE LIBROS
 */
const LIBROS_CARDS_RENDER = {
    renderCard: libro => {
        // 🎯 RESOLVER URL DE IMAGEN (ahora para libros)
        let imagenUrl = resolveImageUrl(libro.URL_IMAGEN_LIBRO, '/assets/img/default-book.png');

        // 🎯 FORMATEAR FECHA
        const fechaFormateada = formatearFecha(libro.FECHA_LIBRO);
        
        // 🎯 PROCESAR AUTORES
        const autoresTexto = libro.autores && libro.autores.length > 0
            ? libro.autores.map(autor => `${autor.NOMBRE_AUTOR} ${autor.APELLIDO_AUTOR}`).join(', ')
            : 'Sin autores';

        // 🎯 PROCESAR CAPÍTULO
        const capituloTexto = libro.CAPITULO_LIBRO ? libro.CAPITULO_LIBRO : 'Libro completo';

        return `
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="/libros/${libro.ID_LIBRO}" class="card-link">
                <div class="product-card">
                    <div class="product-card-img-wrapper">
                        <img src="${imagenUrl}" 
                            class="product-card-img-top" 
                            alt="Portada del libro: ${libro.TITULO_LIBRO}"
                            loading="lazy"
                            onerror="this.src='/assets/img/default-article.png'">
                    </div>
                    
                    <div class="product-card-body">
                        <h5 class="product-card-title">
                            ${libro.TITULO_LIBRO}
                        </h5>
                        
                        <p class="product-card-date">
                            ${fechaFormateada}
                        </p>

                        <hr>
                        
                        <!-- 🎯 TABLA DE METADATOS ESPECÍFICA PARA LIBROS -->
                        <table class="metadata-table-card">
                            <tbody>
                                <tr>
                                    <td>
                                        <i class="fas fa-barcode me-2"></i>ISBN
                                    </td>
                                    <td>
                                        <code class="badge code">${libro.ISBN_LIBRO}</code>
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
 * HANDLER PARA CARDS DE LIBROS
 */
class LibrosCardsHandler extends EntityHandler {
    constructor() {
        super(LIBROS_CARDS_CONFIG, LIBROS_CARDS_RENDER);
    }

    // ✅ OVERRIDE: Datos específicos de libros (no necesarios para vista pública)
    extractButtonData(button) {
        return {};
    }

    // ✅ OVERRIDE: Datos vacíos específicos de libros
    getEmptyData() {
        return {};
    }

    // ✅ OVERRIDE: Configurar archivos específicos de libros (no necesario para vista pública)
    loadFiles(button) {
        // No se necesita para vista pública
    }

    // ✅ OVERRIDE: Procesar datos específicos de libros antes de renderizar
    processDataBeforeRender(data) {
        // Procesar URLs de imagen si es necesario
        if (data.libros && data.libros.data) {
            data.libros.data.forEach(libro => {
                // Ya se procesa en el controlador, pero por seguridad:
                if (libro.URL_IMAGEN_LIBRO && !libro.URL_IMAGEN_LIBRO.startsWith('http')) {
                    if (!libro.URL_IMAGEN_LIBRO.startsWith('/')) {
                        libro.URL_IMAGEN_LIBRO = '/' + libro.URL_IMAGEN_LIBRO;
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
const librosCardsHandler = new LibrosCardsHandler();

document.addEventListener('DOMContentLoaded', async function () {
    await librosCardsHandler.initialize(EntityManager);
    
    // ✅ FUNCIÓN DE DEBUG específica para libros
    window.debugLibrosCards = () => librosCardsHandler.debug();
});

/**
 * EXPORTAR PARA USO GLOBAL
 */
export { librosCardsHandler, LIBROS_CARDS_CONFIG, LIBROS_CARDS_RENDER };