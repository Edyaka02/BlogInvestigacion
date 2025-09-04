/**
 * Funciones para cargar y renderizar datos en formato tabla
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

/**
 * Función para manejar la paginación
 */
export function managePagination(paginacionDiv, contenido = '', selectoresFallback = ['.d-flex.justify-content-end.mt-3']) {
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