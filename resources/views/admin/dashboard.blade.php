{{-- @extends('layouts.admin_layout') --}}
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-5">

        <!-- PUBLICACIONES -->
        <div class="card custom-card-container mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="card-title">Publicaciones</h1>
                </div>
                
                <div class="row">

                    <!-- Sección de Artículos -->
                    <div class="col-md-4 mb-4">
                        <x-card-dashboard title="Artículos" icon="fa-solid fa-book" modalTarget="articuloModal"
                            viewRoute="{{ route('admin.articulos.index') }}">
                            {{-- @include('entities.articulos.modal', ['config' => $config]) --}}
                        </x-card-dashboard>
                        {{-- @include('entities.articulos.modal', ['config' => $config]) --}}
                    </div>

                    <!-- Sección de Libros -->
                    <div class="col-md-4 mb-4">
                        <x-card-dashboard 
                            title="Libros" 
                            icon="fa-solid fa-book" 
                            modalTarget="libroModal"
                            viewRoute="{{ route('admin.libros.index') }}">
                            {{-- @include('entities.libros.modal', ['config' => $config]) --}}
                        </x-card-dashboard>
                    </div>


                    <div class="col-md-4 mb-4">
                        <div class="custom-card card">
                            <div class="card-body">
                                <div class="contenedor">
                                    <i class="bi bi-book-half custom-icon"></i>
                                    <h4 class="card-title">Libros</h4>
                                </div>
                                <div class="custom-button-group">
                                    <div class="btn-group mt-2" role="group" aria-label="Botones de acción">
                                        <button type="button" class="btn custom-button custom-button-subir"
                                            data-bs-toggle="modal" data-bs-target="#libroModal">
                                            <i class="bi bi-upload"></i>
                                            Subir
                                        </button>
                                        <a href="{{ route('admin.libros.index') }}"
                                            class="btn custom-button custom-button-ver">
                                            <i class="bi bi-eye"></i>
                                            Ver todos</a>
                                    </div>
                                </div>
                                {{-- @include('admin.modals.modal_libro') --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- INVESTIGACIONES -->
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title">Investigación</h2>
                <div class="row">
                    <!-- Sección de Proyectos -->
                    <div class="col-md-4 mb-4">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="contenedor">
                                    <i class="bi bi-lightbulb custom-icon"></i>
                                    <h4 class="card-title">Proyectos</h4>
                                </div>
                                <div class="custom-button-group">
                                    <div class="btn-group mt-2" role="group" aria-label="Botones de acción">
                                        <button type="button" class="btn custom-button custom-button-subir"
                                            data-bs-toggle="modal" data-bs-target="#proyectoModal">
                                            <i class="bi bi-upload"></i>
                                            Subir
                                        </button>
                                        <a {{-- href="{{ route('admin.eventos.index') }}"  --}} class="btn custom-button custom-button-ver">
                                            <i class="bi bi-eye"></i>
                                            Ver todos</a>
                                    </div>
                                </div>
                                {{-- @include('admin.modals.modal_proyecto') --}}
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Estancia -->
                    <div class="col-md-4 mb-4">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="contenedor">
                                    <i class="bi bi-globe-americas custom-icon"></i>
                                    <h4 class="card-title">Estancia</h4>
                                </div>
                                <div class="custom-button-group">
                                    <div class="btn-group mt-2" role="group" aria-label="Botones de acción">
                                        <button type="button" class="btn custom-button custom-button-subir"
                                            data-bs-toggle="modal" data-bs-target="#estanciaModal">
                                            <i class="bi bi-upload"></i>
                                            Subir
                                        </button>
                                        <a {{-- href="{{ route('admin.ver_estancia') }}"  --}} class="btn custom-button custom-button-ver">
                                            <i class="bi bi-eye"></i>
                                            Ver todos</a>
                                    </div>
                                </div>
                                {{-- @include('admin.modals.modal_estancia') --}}
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Prototipo -->
                    <div class="col-md-4 mb-4">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="contenedor">
                                    <i class="bi bi-wrench-adjustable-circle custom-icon"></i>
                                    <h4 class="card-title">Prototipo</h4>
                                </div>
                                <div class="custom-button-group">
                                    <div class="btn-group mt-2" role="group" aria-label="Botones de acción">
                                        <button type="button" class="btn custom-button custom-button-subir"
                                            data-bs-toggle="modal" data-bs-target="#prototipoModal">
                                            <i class="bi bi-upload"></i>
                                            Subir
                                        </button>
                                        <a {{-- href="{{ route('admin.ver_prototipo') }}"  --}} class="btn custom-button custom-button-ver">
                                            <i class="bi bi-eye"></i>
                                            Ver todos</a>
                                    </div>
                                </div>
                                {{-- @include('admin.modals.modal_prototipo') --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RED DE INVESTIGACION -->
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title">Red de investigación</h2>
                <div class="row">
                    <!-- Sección de Miembro en redes -->
                    <div class="col-md-4 mb-4">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="contenedor">
                                    <i class="bi bi-people custom-icon"></i>
                                    <h4 class="card-title">Miembro en redes</h4>
                                </div>
                                <div class="custom-button-group">
                                    <div class="btn-group mt-2" role="group" aria-label="Botones de acción">
                                        <button type="button" class="btn custom-button custom-button-subir"
                                            data-bs-toggle="modal" data-bs-target="#miembroModal">
                                            <i class="bi bi-upload"></i>
                                            Subir
                                        </button>
                                        <a {{-- href="{{ route('admin.ver_miembro') }}"  --}} class="btn custom-button custom-button-ver">
                                            <i class="bi bi-eye"></i>
                                            Ver todos</a>
                                    </div>
                                </div>
                                {{-- @include('admin.modals.modal_miembro') --}}
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Reviewer -->
                    <div class="col-md-4 mb-4">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="contenedor">
                                    <i class="bi bi-file-earmark-check custom-icon"></i>
                                    <h4 class="card-title">Reviewer</h4>
                                </div>
                                <div class="custom-button-group">
                                    <div class="btn-group mt-2" role="group" aria-label="Botones de acción">
                                        <button type="button" class="btn custom-button custom-button-subir"
                                            data-bs-toggle="modal" data-bs-target="#reviewerModal">
                                            <i class="bi bi-upload"></i>
                                            Subir
                                        </button>
                                        <a {{-- href="{{ route('admin.ver_reviewer') }}"  --}} class="btn custom-button custom-button-ver">
                                            <i class="bi bi-eye"></i>
                                            Ver todos</a>
                                    </div>
                                </div>
                                {{-- @include('admin.modals.modal_reviewer') --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Eventos -->
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title">Eventos</h2>
                <div class="row">

                    <!-- Sección de Evento -->
                    <div class="col-md-4 mb-4">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="contenedor">
                                    <!--<i class="bi bi-building custom-icon"></i>-->
                                    <i class="bi bi-person-video3 custom-icon"></i>
                                    <h4 class="card-title">Evento</h4>
                                </div>
                                <div class="custom-button-group">
                                    <div class="btn-group mt-2" role="group" aria-label="Botones de acción">
                                        <button type="button" class="btn custom-button custom-button-subir"
                                            data-bs-toggle="modal" data-bs-target="#eventoModal">
                                            <i class="bi bi-upload"></i>
                                            Subir
                                        </button>
                                        <a href="{{ route('admin.eventos.index') }}"
                                            class="btn custom-button custom-button-ver">
                                            <i class="bi bi-eye"></i>
                                            Ver todos</a>
                                    </div>
                                </div>
                                @include('admin.modals.modal_evento', ['config' => $config])
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Concursos -->
                    <div class="col-md-4 mb-4">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="contenedor">
                                    <i class="bi bi-patch-check custom-icon"></i>
                                    <h4 class="card-title">Concurso</h4>
                                </div>
                                <div class="custom-button-group">
                                    <div class="btn-group mt-2" role="group" aria-label="Botones de acción">
                                        <button type="button" class="btn custom-button custom-button-subir"
                                            data-bs-toggle="modal" data-bs-target="#concursoModal">
                                            <i class="bi bi-upload"></i>
                                            Subir
                                        </button>
                                        <a {{-- href="{{ route('admin.ver_concurso') }}"  --}} class="btn custom-button custom-button-ver">
                                            <i class="bi bi-eye"></i>
                                            Ver todos</a>
                                    </div>
                                </div>
                                {{-- @include('admin.modals.modal_concurso') --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RECONOCIMIENTOS -->
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title">Reconocimientos</h2>
                <div class="row">

                    <!-- Sección de Distinciones -->
                    <div class="col-md-4 mb-4">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="contenedor">
                                    <i class="bi bi-award custom-icon"></i>
                                    <h4 class="card-title">Distinciones</h4>
                                </div>
                                <div class="custom-button-group">
                                    <div class="btn-group mt-2" role="group" aria-label="Botones de acción">
                                        <button type="button" class="btn custom-button custom-button-subir"
                                            data-bs-toggle="modal" data-bs-target="#distincionModal">
                                            <i class="bi bi-upload"></i>
                                            Subir
                                        </button>
                                        <a {{-- href="{{ route('admin.ver_distincion') }}" --}} class="btn custom-button custom-button-ver">
                                            <i class="bi bi-eye"></i>
                                            Ver todos</a>
                                    </div>
                                </div>
                                {{-- @include('admin.modals.modal_distincion') --}}
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Premios -->
                    <div class="col-md-4 mb-4">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="contenedor">
                                    <i class="bi bi-trophy custom-icon"></i>
                                    <h4 class="card-title">Premios</h4>
                                </div>
                                <div class="custom-button-group">
                                    <div class="btn-group mt-2" role="group" aria-label="Botones de acción">
                                        <button type="button" class="btn custom-button custom-button-subir"
                                            data-bs-toggle="modal" data-bs-target="#premioModal">
                                            <i class="bi bi-upload"></i>
                                            Subir
                                        </button>
                                        <a {{-- href="{{ route('admin.ver_premio') }}"  --}} href="{{ route('admin.premios.index') }}"
                                            class="btn custom-button custom-button-ver">
                                            <i class="bi bi-eye"></i>
                                            Ver todos</a>
                                    </div>
                                </div>
                                {{-- @include('admin.modals.modal_premio') --}}
                                {{-- @include('entities.premios.modal', ['config' => $config]) --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('modals')
    {{-- Modales para Artículos --}}
    {{-- @include('entities.articulos.modal', ['config' => $config]) --}}
    {{-- @include('entities.articulos.modal') --}}

    {{-- Modales para Libros --}}
    {{-- @include('entities.libros.modal', ['config' => $config]) --}}
@endpush
