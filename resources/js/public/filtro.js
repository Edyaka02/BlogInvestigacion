document.addEventListener('DOMContentLoaded', function () {
    const filtroForm = document.getElementById('filtro-form'); // Formulario de filtros
    const resultadosDiv = document.getElementById('resultados'); // Contenedor de resultados

    if (filtroForm && resultadosDiv) {
        // Escucha el evento de cambio en el formulario
        filtroForm.addEventListener('change', function () {
            cargarResultados();
        });

        // Escucha el evento de clic en el botón de búsqueda
        const buscarBtn = document.getElementById('buscar-btn');
        if (buscarBtn) {
            buscarBtn.addEventListener('click', function () {
                cargarResultados();
            });
        }

        // Función para cargar los resultados
        function cargarResultados(url = '/eventos/filtrar') {
            // Obtén los datos del formulario
            const formData = new FormData(filtroForm);
            const queryParams = new URLSearchParams(Object.fromEntries(formData)).toString();

            // Combina la URL con los parámetros del formulario
            const fullUrl = url.includes('?') ? `${url}&${queryParams}` : `${url}?${queryParams}`;

            // Muestra un spinner de carga
            resultadosDiv.innerHTML = `
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            `;

            // Envía la solicitud AJAX
            axios.get(fullUrl)
                .then(response => {
                    resultadosDiv.innerHTML = ''; // Limpia el spinner

                    if (response.data.eventos.length > 0) {
                        const containerHTML = `
                            <div class="container mt-5">
                                <div class="row justify-content">
                                </div>
                            </div>
                        `;
                        resultadosDiv.innerHTML = containerHTML;

                        const rowDiv = resultadosDiv.querySelector('.row');

                        response.data.eventos.forEach(evento => {
                            const eventoHTML = `
                                <div class="col-md-4 mb-4">
                                    <a href="/eventos/${evento.ID_EVENTO}" class="card-link">
                                        <div class="product-card">
                                            <div class="product-card-img-wrapper">
                                                <img src="${evento.URL_IMAGEN_EVENTO}" class="product-card-img-top" alt="Imagen del evento">
                                            </div>
                                            <div class="product-card-body">
                                                <h5 class="product-card-title">
                                                    ${evento.TITULO_EVENTO}
                                                </h5>
                                                <p class="product-card-date">
                                                    ${evento.FECHA_EVENTO ? new Date(evento.FECHA_EVENTO).toLocaleDateString('es-ES', {
                                                        weekday: 'long',
                                                        year: 'numeric',
                                                        month: 'long',
                                                        day: 'numeric'
                                                    }) : 'Fecha no disponible'}
                                                </p>
                                                <hr>
                                                <p class="product-card-text">
                                                    Tipo: ${evento.TIPO_EVENTO}<br>
                                                    Autores: <br>
                                                    ${evento.autores.map(autor => `${autor.NOMBRE_AUTOR} ${autor.APELLIDO_AUTOR}`).join(', ')}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            `;
                            rowDiv.innerHTML += eventoHTML;
                        });

                        // Agrega la paginación
                        const paginationHTML = generatePagination(response.data, queryParams);
                        resultadosDiv.innerHTML += paginationHTML;

                        // Agrega eventos a los enlaces de paginación
                        const paginationLinks = document.querySelectorAll('.pagination a');
                        paginationLinks.forEach(link => {
                            link.addEventListener('click', function (e) {
                                e.preventDefault();
                                const url = this.getAttribute('href');
                                cargarResultados(url); // Llama a cargarResultados con la URL de la página
                            });
                        });
                    } else {
                        resultadosDiv.innerHTML = '<p>No se encontraron resultados.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error al cargar los resultados:', error);
                    resultadosDiv.innerHTML = '<p>Error al cargar los resultados. Intenta nuevamente.</p>';
                });
        }

        // Función para generar la paginación
        function generatePagination(data, queryParams) {
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
                            <a class="page-link" href="/eventos/filtrar?page=${i}&${queryParams}">${i}</a>
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

        // Carga inicial de resultados
        cargarResultados();
    }
});