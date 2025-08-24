// ✅ MODIFICAR: resources/js/shared/components/paginacion.js
/**
 * Funciones de paginación básicas
 */

export function generatePagination(data, queryParams, urlBase = '') {
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

export function gestionarPaginacion(paginacionDiv, contenido = '', selectoresFallback = ['.d-flex.justify-content-end.mt-3']) {
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

export function addPaginationEventListeners(cargarResultados) {
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            cargarResultados(this.getAttribute('href'));
        });
    });
}

// ✅ MANTENER: Función para tarjetas (se queda aquí por ahora)
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

    const formularioActivo = filtroForm || buscadorForm;

    if (formularioActivo && resultadosDiv) {
        if (filtroForm) {
            filtroForm.addEventListener('change', () => cargarResultados());
            const buscarBtn = filtroForm.querySelector('button[type="submit"], #buscar-btn');
            if (buscarBtn) {
                buscarBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    cargarResultados();
                });
            }
        }

        function cargarResultados(url = urlBase) {
            const formData = new FormData(formularioActivo);
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

                        const paginationHTML = generatePagination(response.data, queryParams, urlBase);
                        resultadosDiv.innerHTML += paginationHTML;
                        addPaginationEventListeners(cargarResultados);
                    } else {
                        resultadosDiv.innerHTML = '<p>No se encontraron resultados.</p>';
                    }
                })
                .catch(() => {
                    resultadosDiv.innerHTML = '<p>Error al cargar los resultados. Intenta nuevamente.</p>';
                });
        }

        // Configurar orden por defecto
        if (filtroForm) {
            const ordenInput = filtroForm.querySelector('[name="orden"]');
            if (ordenInput) {
                const recientesOption = Array.from(ordenInput.options || []).find(opt =>
                    opt.value.toLowerCase().includes('reciente')
                );
                if (recientesOption) {
                    ordenInput.value = recientesOption.value;
                } else {
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