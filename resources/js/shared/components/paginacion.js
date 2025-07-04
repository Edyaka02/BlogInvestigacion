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
    filtroFormId,
    resultadosDivId,
    renderCard,
    key = 'eventos'
}) {
    const filtroForm = document.getElementById(filtroFormId);
    const resultadosDiv = document.getElementById(resultadosDivId);

    if (filtroForm && resultadosDiv) {
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