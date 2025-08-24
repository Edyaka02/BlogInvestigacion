<form id="search-Form" class="d-flex align-items-center gap-2">
    <input type="text" id="searchInput" name="search" class="form-control" placeholder="Buscar..."
        value="{{ request()->query('search') }}">
    <button class="btn custom-button custom-button-primario" type="submit" id="btn-buscar">
        <i class="fa-solid fa-magnifying-glass"></i>
    </button>
</form>
