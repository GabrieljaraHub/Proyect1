@extends('layouts.app')

@section('title', 'Catálogo de Accesorios Informáticos')

@section('content')
<!-- Header y Estadísticas Rápidas -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <h1 class="h3 fw-bold mb-0 text-slate-900">Accesorios Informáticos</h1>
            <span class="badge badge-soft-indigo rounded-pill px-3">{{ $products->total() }} registrados</span>
        </div>
        <p class="text-muted small mb-0 mt-1">Control de catálogo, existencias, precios y distribución por proveedores</p>
    </div>
    <div class="d-flex flex-wrap gap-2 page-header-actions">
        <a href="{{ route('products.create') }}" class="btn-modern-primary">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Nuevo Accesorio</span>
        </a>
    </div>
</div>

<!-- Barra de Búsqueda y Filtros Avanzados (Diseño SENATI) -->
<div class="card-modern p-3 mb-4 bg-white">
    <form action="{{ route('products.index') }}" method="GET" class="row g-2 align-items-end">
        <!-- Búsqueda por nombre o SKU -->
        <div class="col-12 col-md-4">
            <label class="form-label-modern text-muted" style="font-size: 0.78rem;">Buscar</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 10px 0 0 10px;">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text"
                       name="search"
                       class="form-control form-control-modern border-start-0"
                       style="border-radius: 0 10px 10px 0;"
                       placeholder="Nombre o SKU..."
                       value="{{ request('search') }}">
            </div>
        </div>

        <!-- Filtro por Categoría -->
        <div class="col-12 col-sm-6 col-md-3">
            <label class="form-label-modern text-muted" style="font-size: 0.78rem;">Categoría</label>
            <select name="category_id" class="form-select form-select-modern">
                <option value="">Todas las categorías</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Filtro por Proveedor -->
        <div class="col-12 col-sm-6 col-md-3">
            <label class="form-label-modern text-muted" style="font-size: 0.78rem;">Proveedor</label>
            <select name="supplier_id" class="form-select form-select-modern">
                <option value="">Todos los proveedores</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" @selected(request('supplier_id') == $supplier->id)>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Botones de Acción -->
        <div class="col-12 col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100 fw-semibold rounded-3 py-2" title="Aplicar filtros">
                <i class="bi bi-funnel-fill me-1"></i> Filtrar
            </button>
            @if(request()->hasAny(['search', 'category_id', 'supplier_id', 'stock_status']))
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-3 py-2" title="Limpiar filtros">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Contenedor Principal: Tarjeta y Tabla Flotante -->
<div class="card-modern">
    <div class="p-3 px-4 border-bottom bg-white d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-view-list text-primary"></i>
            <span class="fw-bold text-dark">Listado de Existencias</span>
        </div>
        <div class="text-muted small">
            Mostrando {{ $products->count() }} de {{ $products->total() }} registros
        </div>
    </div>

    <div class="table-responsive" style="-webkit-overflow-scrolling: touch;">
        <table class="table table-modern align-middle mb-0">
            <thead>
                <tr>
                    <th style="min-width: 110px;">Código SKU</th>
                    <th style="min-width: 180px;">Accesorio / Detalle</th>
                    <th style="min-width: 120px;">Categoría</th>
                    <th style="min-width: 130px;">Proveedor</th>
                    <th class="text-end" style="min-width: 90px;">Precio</th>
                    <th class="text-center" style="min-width: 120px;">Stock / Mínimo</th>
                    <th class="text-center" style="min-width: 100px;">Estado</th>
                    <th class="text-end pe-4" style="min-width: 100px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <!-- Código SKU -->
                        <td>
                            <span class="badge badge-soft-slate font-monospace px-2 py-1">
                                {{ $product->code }}
                            </span>
                        </td>

                        <!-- Nombre y Descripción -->
                        <td>
                            <div class="fw-bold text-dark">{{ $product->name }}</div>
                            @if($product->description)
                                <div class="text-muted small text-truncate" style="max-width: 250px;" title="{{ $product->description }}">
                                    {{ $product->description }}
                                </div>
                            @endif
                        </td>

                        <!-- Categoría -->
                        <td>
                            <span class="badge badge-soft-indigo d-inline-flex align-items-center gap-1">
                                <i class="bi bi-tag-fill" style="font-size: 0.7rem;"></i>
                                {{ $product->category->name }}
                            </span>
                        </td>

                        <!-- Proveedor -->
                        <td>
                            <div class="text-slate-700 small fw-semibold">
                                <i class="bi bi-truck text-muted me-1"></i>{{ $product->supplier->name }}
                            </div>
                        </td>

                        <!-- Precio -->
                        <td class="text-end">
                            <span class="fw-bold text-dark">S/. {{ number_format($product->price, 2) }}</span>
                        </td>

                        <!-- Stock / Stock Mínimo -->
                        <td class="text-center">
                            <span class="fw-bold fs-6">{{ $product->stock }}</span>
                            <span class="text-muted small">/ {{ $product->min_stock }} un.</span>
                        </td>

                        <!-- Estado con Badge Dinámico -->
                        <td class="text-center">
                            @if($product->stock <= $product->min_stock)
                                <span class="badge badge-soft-rose d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-exclamation-octagon-fill"></i>
                                    <span>Bajo Stock</span>
                                </span>
                            @else
                                <span class="badge badge-soft-emerald d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Disponible</span>
                                </span>
                            @endif
                        </td>

                        <!-- Acciones -->
                        <td class="text-end pe-4">
                            <div class="d-inline-flex align-items-center gap-1">
                                <a href="{{ route('products.edit', $product) }}"
                                   class="btn-action-icon btn-action-edit"
                                   title="Editar este accesorio">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>

                                <form action="{{ route('products.destroy', $product) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Está seguro de eliminar el accesorio: {{ $product->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn-action-icon btn-action-delete"
                                            title="Eliminar este accesorio">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="py-4">
                                <div class="stat-icon bg-light text-muted mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.75rem;">
                                    <i class="bi bi-search"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">No se encontraron productos coincidentes</h6>
                                <p class="text-muted small mb-0">Pruebe ajustando o limpiando los criterios de búsqueda y filtros.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div class="p-3 px-4 border-top bg-white d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Página {{ $products->currentPage() }} de {{ $products->lastPage() }}
            </div>
            <div>{{ $products->links() }}</div>
        </div>
    @endif
</div>
@endsection
