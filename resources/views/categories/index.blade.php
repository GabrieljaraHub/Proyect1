@extends('layouts.app')

@section('title', 'Gestión de Categorías')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2">
            <h1 class="h3 fw-bold mb-0 text-dark">Categorías de Accesorios</h1>
            <span class="badge badge-soft-indigo rounded-pill px-3">{{ $categories->total() }} registradas</span>
        </div>
        <p class="text-muted small mb-0 mt-1">Clasificación técnica para periféricos, memorias, conectividad y componentes</p>
    </div>
    <a href="{{ route('categories.create') }}" class="btn-modern-primary">
        <i class="bi bi-plus-circle-fill"></i>
        <span>Nueva Categoría</span>
    </a>
</div>

<div class="card-modern">
    <div class="p-3 px-4 border-bottom bg-white d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-tags text-primary"></i>
            <span class="fw-bold text-dark">Clasificaciones Activas</span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-modern align-middle">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Nombre de la Categoría</th>
                    <th>Descripción</th>
                    <th class="text-center">Productos Vinculados</th>
                    <th class="text-end pe-4" style="width: 130px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td><span class="text-muted fw-semibold">#{{ $category->id }}</span></td>
                        <td>
                            <span class="fw-bold text-dark">{{ $category->name }}</span>
                        </td>
                        <td>
                            <span class="text-muted small">{{ $category->description ?? 'Sin descripción detallada' }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-soft-indigo">
                                <i class="bi bi-box-seam me-1"></i>
                                {{ $category->products_count }} {{ Str::plural('accesorio', $category->products_count) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex align-items-center gap-1">
                                <a href="{{ route('categories.edit', $category) }}" class="btn-action-icon btn-action-edit" title="Editar">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar esta categoría?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-icon btn-action-delete" title="Eliminar">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                            No hay categorías registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
        <div class="p-3 px-4 border-top bg-white">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection
