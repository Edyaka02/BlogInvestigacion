// /**
//  * Funciones para cargar y renderizar datos en formato tabla
//  */
// function generatePagination(data, queryParams, urlBase = '') {
//     if (data.total > 0) {
//         let paginationHTML = `
//             <nav>
//                 <ul class="pagination justify-content-center">
//         `;

//         if (data.prev_page_url) {
//             paginationHTML += `
//                 <li class="page-item">
//                     <a class="page-link" href="${data.prev_page_url}&${queryParams}" aria-label="Anterior">
//                         <span aria-hidden="true">&laquo;</span>
//                     </a>
//                 </li>
//             `;
//         }

//         for (let i = 1; i <= data.last_page; i++) {
//             paginationHTML += `
//                 <li class="page-item ${data.current_page === i ? 'active' : ''}">
//                     <a class="page-link" href="${urlBase}?page=${i}&${queryParams}">${i}</a>
//                 </li>
//             `;
//         }

//         if (data.next_page_url) {
//             paginationHTML += `
//                 <li class="page-item">
//                     <a class="page-link" href="${data.next_page_url}&${queryParams}" aria-label="Siguiente">
//                         <span aria-hidden="true">&raquo;</span>
//                     </a>
//                 </li>
//             `;
//         }

//         paginationHTML += `
//                 </ul>
//             </nav>
//         `;

//         return paginationHTML;
//     }

//     return '';
// }

// /**
//  * Función para manejar la paginación
//  */
// function managePagination(paginacionDiv, contenido = '', selectoresFallback = ['.d-flex.justify-content-end.mt-3']) {
//     if (paginacionDiv) {
//         paginacionDiv.innerHTML = contenido;
//     } else {
//         for (const selector of selectoresFallback) {
//             const container = document.querySelector(selector);
//             if (container) {
//                 container.innerHTML = contenido;
//                 break;
//             }
//         }
//     }
// }

import {
    generatePagination,
    managePagination
} from '../pagination/pagination.js';

/**
 * Función para cargar los resultados en la tabla
 */
export function loadTableResults({
    urlBase,
    buscadorFormId,
    filtroFormId,
    resultadosDivId,
    paginacionDivId = null,
    key,
    cargarInicialmente = true,
    renderHeader,
    renderRow,
    entityType = 'elemento',
    entityRoute = 'elementos'
}) {
    const filtroForm = document.getElementById(filtroFormId);
    const buscadorForm = document.getElementById(buscadorFormId);
    const resultadosDiv = document.getElementById(resultadosDivId);
    const paginacionDiv = paginacionDivId ? document.getElementById(paginacionDivId) : null;

    // ✅ Verificar que al menos uno de los formularios existe
    if ((filtroForm || buscadorForm) && resultadosDiv) {

        // ✅ Event listeners para el formulario de filtros
        if (filtroForm) {
            filtroForm.addEventListener('change', function (e) {
                console.log('Cambio en filtro:', e.target.name, e.target.value);

                if (e.target.name === 'tipo[]') {
                    const tipoCheckboxes = filtroForm.querySelectorAll('input[name="tipo[]"]:checked');
                    const valoresSeleccionados = Array.from(tipoCheckboxes).map(cb => cb.value);
                    console.log('Tipos seleccionados actualmente:', valoresSeleccionados);
                }
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

            console.log('=== DATOS FINALES QUE SE ENVÍAN ===');
            for (const [key, value] of allFormData.entries()) {
                console.log(`${key}: ${value}`);
            }

            const queryParams = new URLSearchParams(allFormData).toString();
            const fullUrl = url.includes('?') ? `${url}&${queryParams}` : `${url}?${queryParams}`;
            
            console.log('URL completa:', fullUrl);

            // Mostrar overlay de carga
            const loadingOverlay = document.getElementById('table-loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.style.display = 'flex';
            }

            resultadosDiv.innerHTML = '';

            // Tiempo mínimo de carga para que no parpadee
            const minLoadTime = 800;
            const startTime = Date.now();

            axios.get(fullUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                const elapsedTime = Date.now() - startTime;
                const remainingTime = Math.max(0, minLoadTime - elapsedTime);

                setTimeout(() => {
                    // Ocultar overlay
                    if (loadingOverlay) {
                        loadingOverlay.style.display = 'none';
                    }
                    
                    const items = response.data[key];

                    if (items.data && items.data.length > 0) {
                        // Construir tabla completa
                        let tablaHTML = '<div class="table-responsive"><table class="table-custom align-middle w-100">';

                        // Agregar header si existe
                        if (renderHeader) {
                            tablaHTML += renderHeader();
                        }

                        // Agregar tbody con las filas
                        tablaHTML += '<tbody>';
                        items.data.forEach(item => {
                            tablaHTML += renderRow(item);
                        });
                        tablaHTML += '</tbody></table></div>';

                        // Renderizar tabla completa
                        resultadosDiv.innerHTML = tablaHTML;

                        // Generar paginación
                        const paginationHTML = generatePagination(items, queryParams, urlBase);
                        managePagination(paginacionDiv, paginationHTML);

                        // Agregar event listeners a paginación
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

        // Cargar resultados iniciales
        if (cargarInicialmente) {
            loadResults();
        }

        // ✅ DEVOLVER: Objeto con métodos de control
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