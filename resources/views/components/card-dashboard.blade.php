<div class="card custom-card">
    <div class="card-body">
        <div class="contenedor">
            <i class="{{ $icon }} custom-icon"></i>
            <h3 class="card-title">{{ $title }}</h3>
        </div>
        <div class="custom-button-group">
            <div class="btn-group mt-2" role="group" aria-label="Botones de acción">
                @if ($modalTarget)
                    <button type="button" class="btn custom-button custom-button-subir"
                        data-bs-toggle="modal" data-bs-target="#{{ $modalTarget }}">
                        <i class="fa-solid fa-upload"></i>
                        Subir
                    </button>
                @endif
                @if ($viewRoute)
                    <a href="{{ $viewRoute }}" class="btn custom-button custom-button-ver">
                        <i class="fa-solid fa-eye"></i>
                        Ver todos
                    </a>
                @endif
            </div>
        </div>
        {{ $slot }}
    </div>
</div>