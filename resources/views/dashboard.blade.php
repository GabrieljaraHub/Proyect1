@extends('layouts.app')

@section('title', 'Dashboard Principal')

@section('content')
<!-- Saludo y Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1 text-slate-900">
            Panel de Control | <span class="text-primary">{{ Auth::user()->name ?? 'Administrador' }}</span>
        </h1>
        <p class="text-muted small mb-0">Resumen operativo, métricas de inventario y alertas tempranas de stock crítico.</p>
    </div>
    <div class="d-flex flex-wrap gap-2 page-header-actions">
        <a href="{{ route('stock-movements.index') }}" class="btn-modern-secondary">
            <i class="bi bi-arrow-left-right"></i>
            <span>Kardex / Movimientos</span>
        </a>
        <a href="{{ route('products.create') }}" class="btn-modern-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Nuevo Accesorio</span>
        </a>
    </div>
</div>

<!-- Tarjetas de Resumen Visuales (KPIs) — 1 col móvil | 2 col tablet | 4 col escritorio -->
<div class="row g-3 mb-4">
    <!-- Total Productos -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary flex-shrink-0">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="min-w-0">
                <div class="text-muted small fw-semibold text-uppercase">Total Productos</div>
                <div class="fs-3 fw-bold text-dark">{{ $totalProducts }}</div>
                <div class="text-muted" style="font-size: 0.72rem;">Artículos en catálogo</div>
            </div>
        </div>
    </div>

    <!-- Total Categorías -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon bg-success bg-opacity-10 text-success flex-shrink-0">
                <i class="bi bi-tags"></i>
            </div>
            <div class="min-w-0">
                <div class="text-muted small fw-semibold text-uppercase">Categorías</div>
                <div class="fs-3 fw-bold text-dark">{{ $totalCategories }}</div>
                <div class="text-muted" style="font-size: 0.72rem;">Familias de accesorios</div>
            </div>
        </div>
    </div>

    <!-- Total Proveedores -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon bg-info bg-opacity-10 text-info flex-shrink-0">
                <i class="bi bi-truck"></i>
            </div>
            <div class="min-w-0">
                <div class="text-muted small fw-semibold text-uppercase">Proveedores</div>
                <div class="fs-3 fw-bold text-dark">{{ $totalSuppliers }}</div>
                <div class="text-muted" style="font-size: 0.72rem;">Distribuidores mayoristas</div>
            </div>
        </div>
    </div>

    <!-- Alerta Stock Crítico -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card border-danger border-opacity-25">
            <div class="stat-icon bg-danger bg-opacity-10 text-danger flex-shrink-0">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="min-w-0">
                <div class="text-muted small fw-semibold text-uppercase">Stock Crítico</div>
                <div class="fs-3 fw-bold text-danger">{{ $lowStockCount }}</div>
                <div class="text-muted" style="font-size: 0.72rem;">Existencias ≤ mínimo</div>
            </div>
        </div>
    </div>
</div>

<!-- Dos Tablas Secundarias -->
<div class="row g-4 mb-4">
    <!-- Distribución por Categoría -->
    <div class="col-12 col-lg-5">
        <div class="card-modern h-100">
            <div class="p-3 px-4 border-bottom bg-white d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-pie-chart-fill text-primary"></i>
                    <h6 class="fw-bold text-dark mb-0">Distribución por Categoría</h6>
                </div>
                <span class="badge badge-soft-indigo">{{ $totalCategories }} familias</span>
            </div>

            <div class="table-responsive" style="-webkit-overflow-scrolling: touch;">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Categoría</th>
                            <th class="text-center">Cant.</th>
                            <th class="text-center" style="min-width: 130px;">Participación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categoryDistribution as $cat)
                            @php
                                $percent = $totalProducts > 0 ? round(($cat->products_count / $totalProducts) * 100) : 0;
                            @endphp
                            <tr>
                                <td>
                                    <span class="fw-bold text-dark">{{ $cat->name }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">{{ $cat->products_count }} unid.</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percent }}%;"></div>
                                        </div>
                                        <span class="small text-muted fw-semibold" style="font-size: 0.72rem; white-space: nowrap;">{{ $percent }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">No hay categorías registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3 bg-light border-top mt-auto">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-muted fw-bold text-uppercase">Valorización Total:</span>
                    <span class="fs-6 fw-bold text-success">S/. {{ number_format($inventoryValue, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos con Stock Crítico -->
    <div class="col-12 col-lg-7">
        <div class="card-modern h-100">
            <div class="p-3 px-4 border-bottom bg-white d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-shield-exclamation text-danger"></i>
                    <h6 class="fw-bold text-dark mb-0">Productos con Stock Crítico (≤ mín.)</h6>
                </div>
                <span class="badge badge-soft-rose">{{ $lowStockCount }} en alerta</span>
            </div>

            <div class="table-responsive" style="-webkit-overflow-scrolling: touch;">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="min-width: 160px;">Producto / SKU</th>
                            <th class="text-center" style="min-width: 70px;">Stock</th>
                            <th class="text-center" style="min-width: 70px;">Mínimo</th>
                            <th style="min-width: 120px;">Proveedor</th>
                            <th class="text-end pe-4" style="min-width: 100px;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStockProducts as $lowProd)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $lowProd->name }}</div>
                                    <span class="badge badge-soft-slate font-monospace" style="font-size: 0.7rem;">
                                        {{ $lowProd->code }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-soft-rose fw-bold">
                                        {{ $lowProd->stock }} un.
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="text-muted small fw-semibold">
                                        {{ $lowProd->min_stock }} un.
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted small">{{ $lowProd->supplier->name ?? '—' }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('stock-movements.index') }}" class="btn btn-sm btn-outline-primary py-1 px-2" title="Reabastecer con entrada">
                                        <i class="bi bi-arrow-down-left me-1"></i>Abastecer
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-check-circle text-success fs-3 d-block mb-1"></i>
                                    ¡Excelente! No hay accesorios con stock bajo en este momento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Auditoría: Últimos Movimientos Realizados -->
<div class="card-modern">
    <div class="p-3 px-4 border-bottom bg-white d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-clock-history text-primary"></i>
            <h6 class="fw-bold text-dark mb-0">Últimos Movimientos de Stock Realizados</h6>
        </div>
        <a href="{{ route('stock-movements.index') }}" class="btn btn-sm btn-link text-decoration-none">
            Ver Kardex Completo &rarr;
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-modern align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 150px;">Fecha y Hora</th>
                    <th>Accesorio</th>
                    <th class="text-center">Operación</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Trazabilidad (Ant &rarr; Post)</th>
                    <th>Motivo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentMovements as $mov)
                    <tr>
                        <td class="font-monospace small text-muted">
                            {{ $mov->created_at->format('Y-m-d H:i') }}
                        </td>
                        <td class="fw-semibold text-dark">
                            {{ $mov->product->name }}
                        </td>
                        <td class="text-center">
                            @if($mov->type === 'entrada')
                                <span class="badge badge-soft-emerald"><i class="bi bi-arrow-down-left me-1"></i>Entrada</span>
                            @else
                                <span class="badge badge-soft-rose"><i class="bi bi-arrow-up-right me-1"></i>Salida</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <strong class="{{ $mov->type === 'entrada' ? 'text-success' : 'text-danger' }}">
                                {{ $mov->type === 'entrada' ? '+' : '-' }}{{ $mov->quantity }}
                            </strong>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-muted border">
                                {{ $mov->stock_before }} &rarr; {{ $mov->stock_after }}
                            </span>
                        </td>
                        <td class="text-muted small">
                            {{ $mov->reason ?? '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            No se registran movimientos recientes.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
