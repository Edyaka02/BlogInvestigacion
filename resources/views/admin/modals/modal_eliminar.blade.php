<!-- filepath: /c:/laragon/www/BlogInvestigacion/resources/views/admin/modals/modal_eliminar.blade.php -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <h5 class="modal-title" id="modalEliminarLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- ¿Estás seguro de que quieres eliminar este artículo? --}}
                <div id="modalEliminarBody">
                    {{-- ¿Estás seguro de que quieres eliminar este artículo? --}}
                </div>
                
                <form id="formEliminar" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id_elemento" id="id_eliminar">
                    <button type="submit" class="btn custom-button-eliminar mt-3" style="float: right;">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>