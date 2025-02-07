@extends('layouts.admin_layout')

@section('title', 'Basurero')

@section('content')
    <div class="container mt-5 flex-grow-1 d-flex flex-column">
        <h1 class="h-white mb-3 tracking-in-expand">Basurero</h1>

        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-3">Elementos Eliminados</h2>

                @include('admin.components.buscador_filtrado', ['route' => $route])

                <div class="table-responsive">
                    <table class="table align-middle table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Título</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @foreach ($eliminados as $elemento)
                                <tr>
                                    <td>{{ $elemento->id }}</td>
                                    <td>{{ $elemento->titulo }}</td>
                                    <td>{{ ucfirst($elemento->tipo) }}</td>
                                    <td>
                                        <div class="btn-group mt-2" role="group">
                                            <button type="button" class="btn custom-button-subir" data-bs-toggle="modal"
                                                data-bs-target="#modalEliminar" 
                                                {{-- data-id="{{ $row->ID_ARTICULO }}"
                                                data-type="artículo" --}}
                                                >
                                                <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                                            </button>
                                            <button type="button" class="btn custom-button-eliminar" data-bs-toggle="modal"
                                                data-bs-target="#modalEliminar" 
                                                data-id="{{ $elemento->id }}"
                                                data-type="{{ $elemento->tipo }}"
                                                >
                                                <i class="bi bi-trash"></i> Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @include('admin.modals.modal_eliminar')
    </div>
@endsection
