function generatePagination(data, queryParams, urlBase = '') {
    if (data.total > 0) {
        let paginationHTML = `
            <nav>
                <ul class="pagination justify-content-center">
        `;

        if (data.prev_page_url) {
            paginationHTML += `
                <li class="page-item">
                    <a class="page-link" href="${data.prev_page_url}&${queryParams}" aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            `;
        }

        for (let i = 1; i <= data.last_page; i++) {
            paginationHTML += `
                <li class="page-item ${data.current_page === i ? 'active' : ''}">
                    <a class="page-link" href="${urlBase}?page=${i}&${queryParams}">${i}</a>
                </li>
            `;
        }

        if (data.next_page_url) {
            paginationHTML += `
                <li class="page-item">
                    <a class="page-link" href="${data.next_page_url}&${queryParams}" aria-label="Siguiente">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            `;
        }

        paginationHTML += `
                </ul>
            </nav>
        `;

        return paginationHTML;
    }

    return '';
}

export function cargarResultadosGenerico({
    urlBase,
    buscadorFormId,
    filtroFormId,
    resultadosDivId,
    renderCard,
    key = 'eventos'
}) {
    const filtroForm = document.getElementById(filtroFormId);
    const buscadorForm = document.getElementById(buscadorFormId);
    const resultadosDiv = document.getElementById(resultadosDivId);

    // ✅ Priorizar el formulario del offcanvas si existe
    const formularioActivo = filtroOffcanvas || filtroForm;

    // if (filtroForm && resultadosDiv) {
    if (formularioActivo && resultadosDiv) {
        filtroForm.addEventListener('change', () => cargarResultados());
        const buscarBtn = filtroForm.querySelector('button[type="submit"], #buscar-btn');
        if (buscarBtn) {
            buscarBtn.addEventListener('click', function (e) {
                e.preventDefault();
                cargarResultados();
            });
        }

        function cargarResultados(url = urlBase) {
            const formData = new FormData(filtroForm);
            const queryParams = new URLSearchParams(Object.fromEntries(formData)).toString();
            const fullUrl = url.includes('?') ? `${url}&${queryParams}` : `${url}?${queryParams}`;

            resultadosDiv.innerHTML = `
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            `;

            axios.get(fullUrl)
                .then(response => {
                    resultadosDiv.innerHTML = '';
                    const items = response.data[key];
                    if (items.length > 0) {
                        const containerHTML = `
                            <div class="container mt-5">
                                <div class="row justify-content"></div>
                            </div>
                        `;
                        resultadosDiv.innerHTML = containerHTML;
                        const rowDiv = resultadosDiv.querySelector('.row');
                        items.forEach(item => {
                            rowDiv.innerHTML += renderCard(item);
                        });

                        // Paginación (puedes importar generatePagination si la tienes aparte)
                        if (typeof generatePagination === 'function') {
                            const paginationHTML = generatePagination(response.data, queryParams, urlBase);
                            resultadosDiv.innerHTML += paginationHTML;
                            resultadosDiv.querySelectorAll('.pagination a').forEach(link => {
                                link.addEventListener('click', function (e) {
                                    e.preventDefault();
                                    cargarResultados(this.getAttribute('href'));
                                });
                            });
                        }
                    } else {
                        resultadosDiv.innerHTML = '<p>No se encontraron resultados.</p>';
                    }
                })
                .catch(() => {
                    resultadosDiv.innerHTML = '<p>Error al cargar los resultados. Intenta nuevamente.</p>';
                });
        }

        if (filtroForm) {
            // Busca un select o input con name="orden" o similar
            const ordenInput = filtroForm.querySelector('[name="orden"]');
            if (ordenInput) {
                // Si tiene opción "recientes", selecciónala
                const recientesOption = Array.from(ordenInput.options || []).find(opt =>
                    opt.value.toLowerCase().includes('reciente')
                );
                if (recientesOption) {
                    ordenInput.value = recientesOption.value;
                } else {
                    // Si no hay "recientes", busca por título
                    const tituloOption = Array.from(ordenInput.options || []).find(opt =>
                        opt.value.toLowerCase().includes('titulo')
                    );
                    if (tituloOption) {
                        ordenInput.value = tituloOption.value;
                    }
                }
            }
        }


        cargarResultados();
    }
}


export function cargarResultadosTabla({
    urlBase,
    buscadorFormId,
    filtroFormId,
    resultadosDivId,
    paginacionDivId = null,
    key,
    cargarInicialmente = true,
    renderHeader,
    renderRow,
}) {
    const filtroForm = document.getElementById(filtroFormId);
    const buscadorForm = document.getElementById(buscadorFormId);
    const resultadosDiv = document.getElementById(resultadosDivId);
    const paginacionDiv = paginacionDivId ? document.getElementById(paginacionDivId) : null;

    // ✅ Cambiar esta condición para que funcione con cualquiera de los dos
    if ((filtroForm || buscadorForm) && resultadosDiv) {

        // ✅ AGREGAR: Event listeners para el formulario de filtros
        if (filtroForm) {

            filtroForm.addEventListener('change', function (e) {
                console.log('Cambio en filtro:', e.target.name, e.target.value);

                // ✅ AGREGAR: Debug específico para checkboxes tipo
                if (e.target.name === 'tipo[]') {
                    const tipoCheckboxes = filtroForm.querySelectorAll('input[name="tipo[]"]:checked');
                    const valoresSeleccionados = Array.from(tipoCheckboxes).map(cb => cb.value);
                    console.log('Tipos seleccionados actualmente:', valoresSeleccionados);
                }
            });

            // Buscar botón de aplicar filtros
            const aplicarBtn = filtroForm.querySelector('#aplicar-filtros-btn, button[type="submit"]');
            if (aplicarBtn) {
                aplicarBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    console.log('Botón aplicar filtros clickeado');
                    cargarResultados();
                });
            }
        }

        // ✅ AGREGAR: Event listeners para el formulario de búsqueda
        if (buscadorForm) {

            const buscarBtn = buscadorForm.querySelector('#btn-buscar, button[type="submit"]');
            if (buscarBtn) {
                buscarBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    console.log('Botón buscar clickeado');
                    cargarResultados();
                });
            }

        }

        function cargarResultados(url = urlBase) {

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
            console.log('Todas las entradas del FormData:');
            for (const [key, value] of allFormData.entries()) {
                console.log(`${key}: ${value}`);
            }

            const queryParams = new URLSearchParams(allFormData).toString();
            console.log('Query string final:', queryParams);

            const fullUrl = url.includes('?') ? `${url}&${queryParams}` : `${url}?${queryParams}`;
            console.log('URL completa:', fullUrl);

            // Mostrar overlay de carga
            const loadingOverlay = document.getElementById('table-loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.style.display = 'flex';
            }

            // Limpiar resultados previos
            resultadosDiv.innerHTML = '';

            // Tiempo mínimo de carga para que no parpadee (800ms)
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

                            // Construir la tabla completa con header y filas
                            let tablaHTML = '<div class="table-responsive"><table class="table-custom align-middle w-100">';

                            // Agregar header si existe la función renderHeader
                            if (renderHeader) {
                                tablaHTML += renderHeader();
                            }

                            // Agregar tbody con las filas
                            tablaHTML += '<tbody>';

                            // Renderizar filas de la tabla
                            items.data.forEach(item => {
                                // resultadosDiv.innerHTML += renderRow(item);
                                tablaHTML += renderRow(item);
                            });

                            tablaHTML += '</tbody></table></div>';

                            // Renderizar la tabla completa
                            resultadosDiv.innerHTML = tablaHTML;

                            // Generar paginación
                            const paginationHTML = generatePagination(items, queryParams, urlBase);

                            gestionarPaginacion(paginacionDiv, paginationHTML);

                            // Agregar event listeners a los enlaces de paginación
                            document.querySelectorAll('.pagination a').forEach(link => {
                                link.addEventListener('click', function (e) {
                                    e.preventDefault();
                                    cargarResultados(this.getAttribute('href'));
                                });
                            });

                        } else {

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

                            gestionarPaginacion(paginacionDiv, '');
                        }
                    }, remainingTime);

                })
                .catch(error => {
                    const elapsedTime = Date.now() - startTime;
                    const remainingTime = Math.max(0, minLoadTime - elapsedTime);

                    setTimeout(() => {
                        // Ocultar overlay también en caso de error
                        if (loadingOverlay) {
                            loadingOverlay.style.display = 'none';
                        }

                        console.error('Error al cargar resultados:', error);
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

                        gestionarPaginacion(paginacionDiv, '');

                    }, remainingTime);
                });
        }

        // Cargar resultados iniciales
        if (cargarInicialmente) {
            cargarResultados();
        }
    }
}

function gestionarPaginacion(paginacionDiv, contenido = '', selectoresFallback = ['.d-flex.justify-content-end.mt-3']) {
    if (paginacionDiv) {
        paginacionDiv.innerHTML = contenido;
    } else {
        for (const selector of selectoresFallback) {
            const container = document.querySelector(selector);
            if (container) {
                container.innerHTML = contenido;
                break;
            }
        }
    }
}

export function actualizarModal(esEdicion = false) {
    const boton = document.getElementById('btn_modal');
    const icono = document.getElementById('btn_modal_icon');
    const texto = document.getElementById('btn_modal_text');
    const titulo = document.getElementById('modalLabel'); 

    // Verificar que los elementos existen
    if (!boton || !icono || !texto || !titulo) {
        console.error('No se encontraron los elementos del modal');
        return;
    }
    
    if (esEdicion) {
        icono.className = 'fa-solid fa-pen-to-square';
        texto.textContent = 'Actualizar';
        titulo.textContent = 'Actualizar';
        boton.setAttribute('data-modo', 'editar');
    } else {
        icono.className = 'fa-solid fa-upload';
        texto.textContent = 'Crear';
        titulo.textContent = 'Crear';
        boton.setAttribute('data-modo', 'crear');
    }
}

