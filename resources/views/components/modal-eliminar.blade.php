<!-- filepath: /c:/laragon/www/BlogInvestigacion/resources/views/admin/modals/modal_eliminar.blade.php -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content custom-modal-border">
            <div class="modal-header custom-modal-header">
                <h5 class="modal-title" id="modalEliminarLabel">Confirmar eliminación</h5>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                <button type="button" class="btn ms-auto" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="modalEliminarBody">
                </div>
                <form id="formEliminar" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id_elemento" id="id_eliminar">

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="button" class="btn custom-button custom-button-cancelar" data-bs-dismiss="modal"
                            aria-label="Close">
                            <i class="fa-solid fa-xmark"></i> Cancelar
                        </button>
                        <button type="submit" class="btn custom-button custom-button-eliminar">
                            <i class="fa-solid fa-trash-can"></i> Eliminar
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
