@extends('layouts.layout')

@section('title', 'Articulos')

@section('content')
    <h1>Lista de Artículos</h1>
    {{-- <a href="{{ route('articulos.create') }}">Crear Nuevo Artículo</a> --}}
    <ul>
        @foreach($articulos as $articulo)
            <li>
                <a 
                {{-- href="{{ route('articulos.edit', $articulo->ID_ARTICULO) }}" --}}
                >{{ $articulo->TITULO_ARTICULO }}</a>
                <form 
                {{-- action="{{ route('articulos.destroy', $articulo->ID_ARTICULO) }}" --}}
                method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Eliminar</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
