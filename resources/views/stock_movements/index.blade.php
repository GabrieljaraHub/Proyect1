@extends('layouts.app')

@section('title', 'Movimientos de Stock')

@section('content')
<!-- Header del Módulo -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <h1 class="h3 fw-bold mb-0 text-slate-900">Movimientos de Stock</h1>
            <span class="badge badge-soft-indigo rounded-pill px-3">{{ $movements->total() }} registros</span>
        </div>
        <p class="text-muted small mb-0 mt-1">Control de kardex, trazabilidad de entradas y salidas con bloqueo transaccional</p>
    </div>
    <div class="d-flex flex-wrap gap-2 page-header-actions">
        <button class="btn-modern-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMovementForm" aria-expanded="true">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Nuevo Movimiento</span>
        </button>
    </div>
</div>

<!-- Formulario para Registrar Nuevo Movimiento (Card Desplegable / Visible) -->
<div class="collapse show mb-4" id="collapseMovementForm">
    <div class="card-modern">
        <div class="p-3 px-4 border-bottom bg-white d-flex align-items-center gap-2">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary" style="width: 36px; height: 36px; font-size: 1.1rem;">
                <i class="bi bi-arrow-left-right"></i>
            </div>
            <div>
                <h6 class="fw-bold text-dark mb-0">Registrar Entrada o Salida de Inventario</h6>
                <span class="text-muted small" style="font-size: 0.76rem;">El stock se recalculará automáticamente garantizando consistencia</span>
            </div>
        </div>

        <div class="p-4 bg-white">
            <form action="{{ route('stock-movements.store') }}" method="POST" novalidate>
                @csrf

                <div class="row g-3">
                    <!-- Selector de Producto -->
                    <div class="col-12 col-md-5">
                        <label for="product_id" class="form-label-modern">
                            <span>Producto / Accesorio</span>
                            <span class="text-danger small">*</span>
                        </label>
                        <select name="product_id"
                                id="product_id"
                                class="form-select form-select-modern @error('product_id') is-invalid @enderror"
                                required>
                            <option value="" disabled {{ old('product_id') ? '' : 'selected' }}>-- Seleccione Producto --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                        data-stock="{{ $product->stock }}"
                                        @selected(old('product_id') == $product->id)>
                                    {{ $product->name }} (Stock: {{ $product->stock }} | SKU: {{ $product->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback fw-medium">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tipo de Movimiento -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label for="type" class="form-label-modern">
                            <span>Tipo de Operación</span>
                            <span class="text-danger small">*</span>
                        </label>
                        <select name="type"
                                id="type"
                                class="form-select form-select-modern @error('type') is-invalid @enderror"
                                required>
                            <option value="entrada" @selected(old('type') == 'entrada')>
                                🟢 Entrada (Aumenta stock)
                            </option>
                            <option value="salida" @selected(old('type') == 'salida')>
                                🔴 Salida (Descuenta stock)
                            </option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback fw-medium">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Cantidad -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="quantity" class="form-label-modern">
                            <span>Cantidad a Procesar</span>
                            <span class="text-danger small">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number"
                                   name="quantity"
                                   id="quantity"
                                   min="1"
                                   class="form-control form-control-modern @error('quantity') is-invalid @enderror"
                                   value="{{ old('quantity', 1) }}"
                                   placeholder="1"
                                   required>
                            <span class="input-group-text bg-light text-muted small border-start-0" style="border-radius: 0 10px 10px 0; border: 1.5px solid #cbd5e1; border-left: none;">
                                unidades
                            </span>
                            @error('quantity')
                                <div class="invalid-feedback fw-medium">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Motivo / Justificación -->
                    <div class="col-12 col-md-9">
                        <label for="reason" class="form-label-modern">
                            <span>Motivo o Justificación</span>
                            <span class="text-muted small fw-normal">(Opcional)</span>
                        </label>
                        <input type="text"
                               name="reason"
                               id="reason"
                               list="commonReasons"
                               class="form-control form-control-modern @error('reason') is-invalid @enderror"
                               value="{{ old('reason') }}"
                               placeholder="Ej. Compra a proveedor, Venta a cliente, Ajuste...">
                        <datalist id="commonReasons">
                            <option value="Compra a proveedor">
                            <option value="Venta a cliente">
                            <option value="Ajuste de inventario">
                            <option value="Devolución de cliente">
                            <option value="Devolución a proveedor">
                            <option value="Merma o producto defectuoso">
                        </datalist>
                        @error('reason')
                            <div class="invalid-feedback fw-medium">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Botón de Envío -->
                    <div class="col-12 col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn-modern-primary w-100 justify-content-center py-2">
                            <i class="bi bi-check2-circle fs-5"></i>
                            <span>Procesar</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tabla de Historial de Movimientos -->
<div class="card-modern">
    <div class="p-3 px-4 border-bottom bg-white d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-clock-history text-primary"></i>
            <span class="fw-bold text-dark">Historial de Trazabilidad (Kardex)</span>
        </div>
        <div class="text-muted small">
            Mostrando página {{ $movements->currentPage() }} de {{ $movements->lastPage() }}
        </div>
    </div>

    <div class="table-responsive" style="-webkit-overflow-scrolling: touch;">
        <table class="table table-modern align-middle">
            <thead>
                <tr>
                    <th style="min-width: 145px;">Fecha y Hora</th>
                    <th style="min-width: 180px;">Producto / Accesorio</th>
                    <th class="text-center" style="min-width: 100px;">Tipo</th>
                    <th class="text-center" style="min-width: 90px;">Cantidad</th>
                    <th class="text-center" style="min-width: 155px;">Stock (Ant. &rarr; Nuevo)</th>
                    <th style="min-width: 140px;">Motivo / Razón</th>
                    <th class="text-center pe-4" style="min-width: 90px;">Usuario</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $movement)
                    <tr>
                        <!-- Fecha y Hora -->
                        <td>
                            <div class="fw-semibold text-dark small font-monospace">
                                <i class="bi bi-calendar3 text-muted me-1"></i>
                                {{ $movement->created_at->format('Y-m-d H:i') }}
                            </div>
                            <div class="text-muted" style="font-size: 0.72rem;">
                                hace {{ $movement->created_at->diffForHumans() }}
                            </div>
                        </td>

                        <!-- Producto -->
                        <td>
                            <div class="fw-bold text-dark">{{ $movement->product->name }}</div>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span class="badge badge-soft-slate font-monospace" style="font-size: 0.7rem;">
                                    {{ $movement->product->code }}
                                </span>
                                @if($movement->product->category)
                                    <span class="text-muted small" style="font-size: 0.75rem;">
                                        &bull; {{ $movement->product->category->name }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Tipo de Movimiento -->
                        <td class="text-center">
                            @if($movement->type === 'entrada')
                                <span class="badge badge-soft-emerald d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-arrow-down-left"></i>
                                    <span>Entrada</span>
                                </span>
                            @else
                                <span class="badge badge-soft-rose d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-arrow-up-right"></i>
                                    <span>Salida</span>
                                </span>
                            @endif
                        </td>

                        <!-- Cantidad -->
                        <td class="text-center">
                            @if($movement->type === 'entrada')
                                <span class="fw-bold text-success fs-6">+{{ $movement->quantity }}</span>
                            @else
                                <span class="fw-bold text-danger fs-6">-{{ $movement->quantity }}</span>
                            @endif
                            <span class="text-muted small d-block" style="font-size: 0.7rem;">unid.</span>
                        </td>

                        <!-- Trazabilidad: Stock Antes -> Después -->
                        <td class="text-center">
                            <div class="d-inline-flex align-items-center gap-2 px-2 py-1 bg-light rounded-pill border">
                                <span class="text-muted fw-semibold small">{{ $movement->stock_before }}</span>
                                <i class="bi bi-arrow-right text-primary small"></i>
                                <span class="fw-bold text-dark small">{{ $movement->stock_after }}</span>
                            </div>
                        </td>

                        <!-- Motivo -->
                        <td>
                            @if($movement->reason)
                                <span class="text-dark small">{{ $movement->reason }}</span>
                            @else
                                <span class="text-muted small fst-italic">Sin motivo especificado</span>
                            @endif
                        </td>

                        <!-- Usuario que operó -->
                        <td class="text-center pe-4">
                            <span class="badge bg-light text-secondary border">admin</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="py-4">
                                <div class="stat-icon bg-light text-muted mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.75rem;">
                                    <i class="bi bi-arrow-left-right"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Aún no se registran movimientos de stock</h6>
                                <p class="text-muted small mb-0">Utilice el formulario superior para registrar la primera entrada o salida de almacén.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($movements->hasPages())
        <div class="p-3 px-4 border-top bg-white d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Mostrando {{ $movements->firstItem() }} a {{ $movements->lastItem() }} de {{ $movements->total() }} movimientos
            </div>
            <div>
                {{ $movements->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
