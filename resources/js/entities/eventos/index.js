import { cargarResultadosGenerico } from '../../shared/components/paginacion.js';

cargarResultadosGenerico({
    urlBase: '/eventos/filtrar',
    filtroFormId: 'filtro-form',
    resultadosDivId: 'resultados',
    key: 'eventos',
    renderCard: evento => `
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
    `
});