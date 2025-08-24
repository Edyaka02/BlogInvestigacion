// ✅ MODIFICAR: resources/js/entities/articulos/edit.js
import { cargarResultadosTabla } from '../../shared/components/cargarTabla.js';
import { actualizarModal, setupDeleteModal } from '../../modal.js';
import { ModalFormCleaner } from '../../shared/utils/validationHandler.js';

// ✅ AGREGAR: Importar Sonner directamente aquí
// ✅ CAMBIAR: Importar toast Y Toaster
import { toast, Toaster } from 'sonner';

// ✅ FUNCIONES: Sonner simples aquí mismo
function toastSuccess(message) {
    return toast.success(message, {
        duration: 4000,
        position: 'top-right'
    });
}

function toastError(message) {
    return toast.error(message, {
        duration: 5000,
        position: 'top-right'
    });
}

function toastInfo(message) {
    return toast.info(message, {
        duration: 4000,
        position: 'top-right'
    });
}

// ✅ INICIALIZAR: Toaster de Sonner
function initSonnerToaster() {
    // Crear contenedor para Sonner si no existe
    if (!document.querySelector('[data-sonner-toaster]')) {
        console.log('🎯 Creando Toaster de Sonner...');

        // Crear div contenedor
        const toasterDiv = document.createElement('div');
        toasterDiv.setAttribute('data-sonner-toaster', 'true');
        toasterDiv.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
            pointer-events: none;
        `;
        document.body.appendChild(toasterDiv);

        console.log('✅ Toaster de Sonner creado');
    }
}

// ✅ HACER: Disponible globalmente
window.toastSuccess = toastSuccess;
window.toastError = toastError;
window.toastInfo = toastInfo;

// Solo cargar modal handlers cuando sea necesario
let modalHandlersCargados = false;
let tablaController;

async function cargarModalHandlers() {
    if (!modalHandlersCargados) {
        console.log('🔄 Cargando modal handlers para artículos...');
        try {
            const { initArticuloModalHandlers } = await import('./modal.js');
            initArticuloModalHandlers();
            modalHandlersCargados = true;
            console.log('✅ Modal handlers cargados exitosamente');
        } catch (error) {
            console.error('❌ Error al cargar modal handlers:', error);
        }
    }
}

tablaController = cargarResultadosTabla({
    urlBase: '/dashboard/articulos',
    buscadorFormId: 'search-Form',
    filtroFormId: 'filtro-form',
    resultadosDivId: 'tabla-resultados',
    paginacionDivId: 'paginacion-container',
    key: 'articulos',
    cargarInicialmente: true,
    renderHeader: () => `
        <thead>
            <tr>
                <th>Título</th>
                <th>ISSN</th>
                <th>Fecha</th>
                <th>Revista</th>
                <th>Tipo</th>
                <th>Acciones</th>
            </tr>
        </thead>
    `,
    renderRow: articulo => `
        <tr>
            <td>${articulo.TITULO_ARTICULO}</td>
            <td>${articulo.ISSN_ARTICULO}</td>
            <td>${articulo.FECHA_ARTICULO}</td>
            <td>${articulo.REVISTA_ARTICULO}</td>
            <td>${articulo.tipo ? articulo.tipo.NOMBRE_TIPO : ''}</td>
            <td>
                <div class="d-flex">
                    <div class="ms-2">
                        <button type="button" class="btn custom-button custom-button-editar"
                            data-bs-toggle="modal" data-bs-target="#articuloModal"
                            onclick="window.prepararModal()"
                            data-id="${articulo.ID_ARTICULO}" 
                            data-issn="${articulo.ISSN_ARTICULO}"
                            data-titulo="${articulo.TITULO_ARTICULO}"
                            data-resumen="${articulo.RESUMEN_ARTICULO || ''}"
                            data-fecha="${articulo.FECHA_ARTICULO}"
                            data-revista="${articulo.REVISTA_ARTICULO}"
                            data-tipo="${articulo.ID_TIPO || ''}"
                            data-url-revista="${articulo.URL_REVISTA_ARTICULO || ''}"
                            data-url-articulo="${articulo.URL_ARTICULO || ''}"
                            data-url-imagen="${articulo.URL_IMAGEN_ARTICULO || ''}"
                            data-nombres-autores="${articulo.autores ? articulo.autores.map(a => a.NOMBRE_AUTOR).join(',') : ''}"
                            data-apellidos-autores="${articulo.autores ? articulo.autores.map(a => a.APELLIDO_AUTOR).join(',') : ''}"
                            data-orden-autores="${articulo.autores ? articulo.autores.map(a => a.pivot?.ORDEN_AUTOR || '').join(',') : ''}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </div>
                    <div class="ms-2">
                        <button type="button" class="btn custom-button custom-button-eliminar"
                            data-bs-toggle="modal" data-bs-target="#modalEliminar"
                            data-id="${articulo.ID_ARTICULO}" 
                            data-type="artículo"
                            data-route="articulos">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </td>
        </tr>
    `
});

// Preparar modal antes de abrirlo
window.prepararModal = async function () {
    await cargarModalHandlers();
};

document.addEventListener('DOMContentLoaded', function () {

    // ✅ AGREGAR: Inicializar Toaster PRIMERO
    initSonnerToaster();
    console.log('✅ Toaster de Sonner inicializado');

    // ✅ MOSTRAR: Mensaje guardado después de recarga
    setTimeout(() => {
        mostrarMensajeSonner();
    }, 500);

    // ✅ PROBAR: Sonner al cargar (temporal para verificar)
    setTimeout(() => {
        console.log('🎯 Ejecutando toast de prueba...');
        toastInfo("Sonner funcionando en edit.js");
    }, 1000);

    // Configuración de modal eliminar

    if (document.getElementById('modalEliminar')) {
        setupDeleteModal('modalEliminar', 'formEliminar');
        console.log('✅ Modal eliminar configurado');
    }

    // handlers si el modal ya existe en la página
    const modal = document.getElementById('articuloModal');
    if (modal) {
        cargarModalHandlers();
    }

    // Si hay botón crear, pre-cargar handlers
    const btnCrear = document.querySelector('button[data-bs-target="#articuloModal"]:not([data-id])');
    if (btnCrear) {
        btnCrear.addEventListener('click', cargarModalHandlers, { once: true });
    }

    document.getElementById('articuloModal').addEventListener('show.bs.modal', function (event) {
        // ✅ AGREGAR: Limpiar validaciones al abrir modal
        ModalFormCleaner.clearAll('articuloModal');

        var button = event.relatedTarget;

        if (button && button.getAttribute('data-id')) {
            // MODO EDICIÓN
            var data = {
                id_articulo: button.getAttribute('data-id'),
                issn_articulo: button.getAttribute('data-issn'),
                titulo_articulo: button.getAttribute('data-titulo'),
                resumen_articulo: button.getAttribute('data-resumen'),
                fecha_articulo: button.getAttribute('data-fecha'),
                revista_articulo: button.getAttribute('data-revista'),
                id_tipo: button.getAttribute('data-tipo'),
                url_revista_articulo: button.getAttribute('data-url-revista')
            };

            var modal = this;
            var form = modal.querySelector('#articuloForm');

            window.configureFormForEdit(form, data.id_articulo, 'articulos');
            window.setModalData(modal, data);

            window.updateFileDisplay('file-articulo', button.getAttribute('data-url-articulo'), 'No se ha elegido un artículo');
            window.updateFileDisplay('file-imagen', button.getAttribute('data-url-imagen'), 'No se ha elegido una imagen');

            actualizarModal(true, { entidad: 'Artículo' });

        } else {
            // MODO CREACIÓN
            console.log('Modal abierto para crear nuevo artículo');

            var modal = this;
            var form = modal.querySelector('#articuloForm');

            form.reset();
            document.getElementById('id_articulo').value = '';

            window.configureFormForCreate(form, 'articulos');

            window.updateFileDisplay('file-articulo', '', 'No se ha elegido un artículo');
            window.updateFileDisplay('file-imagen', '', 'No se ha elegido una imagen');

            actualizarModal(false, { entidad: 'Artículo' });
        }
    });

    document.getElementById('articuloModal').addEventListener('hidden.bs.modal', function () {
        actualizarModal(false, { entidad: 'Artículo' });

        // ✅ AGREGAR: Limpiar validaciones al cerrar modal
        ModalFormCleaner.clearAll('articuloModal');
    });

    // ✅ FUNCIÓN GLOBAL: Usar controlador existente para recargar
    window.recargarTablaArticulos = function () {
        console.log('🔄 Recargando tabla usando cargarResultadosTabla...');
        if (tablaController && typeof tablaController.recargar === 'function') {
            tablaController.recargar();
            console.log('✅ Tabla recargada exitosamente');
        } else {
            console.log('⚠️ Controlador no disponible, recargando página...');
            window.location.reload();
        }
    };

    // ✅ LISTENER: Para evento de recarga específica
    document.addEventListener('reloadTableOnly', function (e) {
        if (e.detail.entityType === 'articulos' || e.detail.entityType === 'artículo') {
            console.log('🔄 Evento reloadTableOnly recibido');
            window.recargarTablaArticulos();
        }
    });

    // ✅ MODIFICAR: Listener general - usar función sin recarga de página
    document.addEventListener('reloadTable', function (e) {
        if (e.detail.entityType === 'articulos') {
            console.log('🔄 Recargando tabla de artículos...');
            window.recargarTablaArticulos(); // ✅ CAMBIAR: En lugar de window.location.reload()
        }
    });
});