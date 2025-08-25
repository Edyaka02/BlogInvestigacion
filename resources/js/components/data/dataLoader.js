import {
    generatePagination,
    managePagination
} from '../pagination/pagination.js';

/**
 * Función genérica para cargar datos en cualquier formato
 */
export function loadDataResults({
    urlBase,
    buscadorFormId,
    filtroFormId,
    resultadosDivId,
    paginacionDivId = null,
    key,
    cargarInicialmente = true,
    format = 'table',           // ✅ NUEVO: 'table', 'cards', 'list'
    renderHeader,               // Para tablas
    renderRow,                  // Para tablas
    renderCard,                 // ✅ NUEVO: Para cards
    renderListItem,             // ✅ NUEVO: Para listas
    containerClass = '',        // ✅ NUEVO: Clases CSS del contenedor
    entityType = 'elemento',
    entityRoute = 'elementos'
}) {
    const filtroForm = document.getElementById(filtroFormId);
    const buscadorForm = document.getElementById(buscadorFormId);
    const resultadosDiv = document.getElementById(resultadosDivId);
    const paginacionDiv = paginacionDivId ? document.getElementById(paginacionDivId) : null;

    if ((filtroForm || buscadorForm) && resultadosDiv) {

        // ✅ Event listeners para el formulario de filtros
        if (filtroForm) {
            filtroForm.addEventListener('change', function (e) {
                console.log('Cambio en filtro:', e.target.name, e.target.value);
            });

            const aplicarBtn = filtroForm.querySelector('#aplicar-filtros-btn, button[type="submit"]');
            if (aplicarBtn) {
                aplicarBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    console.log('Botón aplicar filtros clickeado');
                    loadResults();
                });
            }
        }

        // ✅ Event listeners para el formulario de búsqueda
        if (buscadorForm) {
            const buscarBtn = buscadorForm.querySelector('#btn-buscar, button[type="submit"]');
            if (buscarBtn) {
                buscarBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    console.log('Botón buscar clickeado');
                    loadResults();
                });
            }
        }

        function loadResults(url = urlBase) {
            const allFormData = new FormData();

            // Agregar datos del formulario de filtros
            if (filtroForm) {
                const filtroData = new FormData(filtroForm);
                for (const [key, value] of filtroData.entries()) {
                    allFormData.append(key, value);
                }
            }

            // Agregar datos del formulario de búsqueda
            if (buscadorForm) {
                const buscadorData = new FormData(buscadorForm);
                for (const [key, value] of buscadorData.entries()) {
                    allFormData.append(key, value);
                }
            }

            const queryParams = new URLSearchParams(allFormData).toString();
            const fullUrl = url.includes('?') ? `${url}&${queryParams}` : `${url}?${queryParams}`;

            // Mostrar overlay de carga
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.style.display = 'flex';
            }

            resultadosDiv.innerHTML = '';

            const minLoadTime = 800;
            const startTime = Date.now();

            axios.get(fullUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                const elapsedTime = Date.now() - startTime;
                const remainingTime = Math.max(0, minLoadTime - elapsedTime);

                setTimeout(() => {
                    if (loadingOverlay) {
                        loadingOverlay.style.display = 'none';
                    }
                    
                    const items = response.data[key];

                    if (items.data && items.data.length > 0) {
                        let contenidoHTML = '';

                        // ✅ RENDERIZAR: Según el formato especificado
                        switch (format) {
                            case 'table':
                                contenidoHTML = renderTableFormat(items.data, renderHeader, renderRow);
                                break;
                            
                            case 'cards':
                                contenidoHTML = renderCardsFormat(items.data, renderCard, containerClass);
                                break;
                            
                            case 'list':
                                contenidoHTML = renderListFormat(items.data, renderListItem, containerClass);
                                break;
                            
                            default:
                                console.warn(`Formato no reconocido: ${format}`);
                                contenidoHTML = renderTableFormat(items.data, renderHeader, renderRow);
                        }

                        // ✅ RENDERIZAR: Contenido final
                        resultadosDiv.innerHTML = contenidoHTML;

                        // ✅ PAGINACIÓN: Igual para todos los formatos
                        const paginationHTML = generatePagination(items, queryParams, urlBase);
                        managePagination(paginacionDiv, paginationHTML);

                        // ✅ EVENTOS: Paginación igual para todos
                        document.querySelectorAll('.pagination a').forEach(link => {
                            link.addEventListener('click', function (e) {
                                e.preventDefault();
                                loadResults(this.getAttribute('href'));
                            });
                        });

                    } else {
                        // Sin resultados
                        resultadosDiv.innerHTML = `
                            <div class="d-flex flex-column align-items-center justify-content-center text-center py-5" style="min-height: 300px;">
                                <img src="${window.location.origin}/assets/svg/Not-Found.svg" 
                                    alt="No se encontraron resultados" 
                                    class="img-fluid mb-3" 
                                    style="max-width: 300px;">
                                <h6 class="text-muted mb-2">No se encontraron resultados</h6>
                                <p class="text-muted mb-0 small">Intenta ajustar tus filtros de búsqueda</p>
                            </div>
                        `;
                        managePagination(paginacionDiv, '');
                    }
                }, remainingTime);
            })
            .catch(error => {
                const elapsedTime = Date.now() - startTime;
                const remainingTime = Math.max(0, minLoadTime - elapsedTime);

                setTimeout(() => {
                    if (loadingOverlay) {
                        loadingOverlay.style.display = 'none';
                    }

                    console.error('Error al cargar resultados:', error);
                    resultadosDiv.innerHTML = `
                        <div class="d-flex flex-column align-items-center justify-content-center text-center py-5" style="min-height: 300px;">
                            <img src="${window.location.origin}/assets/svg/Not-Found.svg" 
                                alt="Error de carga" 
                                class="img-fluid mb-3" 
                                style="max-width: 300px;">
                            <h6 class="text-muted mb-2">Error al cargar los datos</h6>
                            <p class="text-muted mb-0 small">Intenta recargar la página</p>
                        </div>
                    `;
                    managePagination(paginacionDiv, '');
                }, remainingTime);
            });
        }

        if (cargarInicialmente) {
            loadResults();
        }

        return {
            recargar: () => loadResults(),
            recargarPaginaActual: () => {
                const urlActual = new URL(window.location);
                loadResults(urlActual.href);
            }
        };
    }

    return null;
}

/**
 * RENDERIZADORES ESPECÍFICOS POR FORMATO
 */
function renderTableFormat(data, renderHeader, renderRow) {
    let html = '<div class="table-responsive"><table class="table-custom align-middle w-100">';
    
    if (renderHeader) {
        html += renderHeader();
    }
    
    html += '<tbody>';
    data.forEach(item => {
        html += renderRow(item);
    });
    html += '</tbody></table></div>';
    
    return html;
}

function renderCardsFormat(data, renderCard, containerClass = '') {
    let html = `<div class="row ${containerClass}">`;
    
    data.forEach(item => {
        html += renderCard(item);
    });
    
    html += '</div>';
    return html;
}

function renderListFormat(data, renderListItem, containerClass = '') {
    let html = `<div class="list-group ${containerClass}">`;
    
    data.forEach(item => {
        html += renderListItem(item);
    });
    
    html += '</div>';
    return html;
}

/**
 * FUNCIONES DE COMPATIBILIDAD
 */
export function loadTableResults(config) {
    return loadDataResults({
        ...config,
        format: 'table'
    });
}

export function loadCardResults(config) {
    return loadDataResults({
        ...config,
        format: 'cards'
    });
}

export function loadListResults(config) {
    return loadDataResults({
        ...config,
        format: 'list'
    });
}