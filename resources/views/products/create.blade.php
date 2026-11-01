@extends('layouts.app')

@section('title', 'Registrar Accesorio Informático')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9 col-xl-8">
        <!-- Navegación de migas de pan (Breadcrumbs) -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none text-muted"><i class="bi bi-box-seam me-1"></i>Inventario</a></li>
                <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Nuevo Accesorio</li>
            </ol>
        </nav>

        <!-- Tarjeta del Formulario -->
        <div class="card-modern">
            <div class="p-4 border-bottom bg-white d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-plus-square-fill"></i>
                </div>
                <div>
                    <h2 class="h5 fw-bold text-dark mb-0">Registrar Accesorio Informático</h2>
                    <p class="text-muted small mb-0">Ingrese los datos técnicos, clasificación, precios y existencias iniciales</p>
                </div>
            </div>

            <div class="p-4 p-md-5 bg-white">
                <form action="{{ route('products.store') }}" method="POST" novalidate>
                    @csrf

                    <!-- SECCIÓN 1: Identificación -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3 text-secondary pb-1 border-bottom">
                            <i class="bi bi-info-circle text-primary"></i>
                            <span class="text-uppercase small fw-bold tracking-wide">1. Identificación del Producto</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="code" class="form-label-modern">
                                    <span>Código / SKU</span>
                                    <span class="text-danger small">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-modern-text">#</span>
                                    <input type="text"
                                           name="code"
                                           id="code"
                                           class="form-control form-control-modern font-monospace text-uppercase @error('code') is-invalid @enderror"
                                           value="{{ old('code') }}"
                                           placeholder="ACC-001"
                                           required>
                                    @error('code')
                                        <div class="invalid-feedback fw-medium">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text text-muted small">Único en el sistema.</div>
                            </div>

                            <div class="col-md-8">
                                <label for="name" class="form-label-modern">
                                    <span>Nombre del Accesorio</span>
                                    <span class="text-danger small">*</span>
                                </label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       class="form-control form-control-modern @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}"
                                       placeholder="Ej. Teclado Mecánico RGB Switch Red"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback fw-medium">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 2: Clasificación y Proveedor -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3 text-secondary pb-1 border-bottom">
                            <i class="bi bi-diagram-3 text-primary"></i>
                            <span class="text-uppercase small fw-bold tracking-wide">2. Clasificación y Proveedor</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="category_id" class="form-label-modern">
                                    <span>Categoría</span>
                                    <span class="text-danger small">*</span>
                                </label>
                                <select name="category_id"
                                        id="category_id"
                                        class="form-select form-select-modern @error('category_id') is-invalid @enderror"
                                        required>
                                    <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>-- Seleccione Categoría --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback fw-medium">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="supplier_id" class="form-label-modern">
                                    <span>Proveedor</span>
                                    <span class="text-danger small">*</span>
                                </label>
                                <select name="supplier_id"
                                        id="supplier_id"
                                        class="form-select form-select-modern @error('supplier_id') is-invalid @enderror"
                                        required>
                                    <option value="" disabled {{ old('supplier_id') ? '' : 'selected' }}>-- Seleccione Proveedor --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback fw-medium">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 3: Precio, Stock Actual y Stock Mínimo -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3 text-secondary pb-1 border-bottom">
                            <i class="bi bi-cash-stack text-primary"></i>
                            <span class="text-uppercase small fw-bold tracking-wide">3. Precios y Parámetros de Stock</span>
                        </div>

                        <div class="row g-3">
                            <!-- Precio -->
                            <div class="col-md-4">
                                <label for="price" class="form-label-modern">
                                    <span>Precio Unitario (S/.)</span>
                                    <span class="text-danger small">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-modern-text">S/.</span>
                                    <input type="number"
                                           name="price"
                                           id="price"
                                           step="0.01"
                                           min="0"
                                           class="form-control form-control-modern @error('price') is-invalid @enderror"
                                           value="{{ old('price') }}"
                                           placeholder="0.00"
                                           required>
                                    @error('price')
                                        <div class="invalid-feedback fw-medium">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Stock Inicial -->
                            <div class="col-md-4">
                                <label for="stock" class="form-label-modern">
                                    <span>Stock Inicial</span>
                                    <span class="text-danger small">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number"
                                           name="stock"
                                           id="stock"
                                           min="0"
                                           class="form-control form-control-modern @error('stock') is-invalid @enderror"
                                           value="{{ old('stock', 0) }}"
                                           required>
                                    <span class="input-group-text bg-light text-muted small border-start-0" style="border-radius: 0 10px 10px 0; border: 1.5px solid #cbd5e1; border-left: none;">
                                        unid.
                                    </span>
                                    @error('stock')
                                        <div class="invalid-feedback fw-medium">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Stock Mínimo (Alerta) -->
                            <div class="col-md-4">
                                <label for="min_stock" class="form-label-modern">
                                    <span>Stock Mínimo (Alerta)</span>
                                    <span class="text-danger small">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number"
                                           name="min_stock"
                                           id="min_stock"
                                           min="0"
                                           class="form-control form-control-modern @error('min_stock') is-invalid @enderror"
                                           value="{{ old('min_stock', 5) }}"
                                           required>
                                    <span class="input-group-text bg-light text-muted small border-start-0" style="border-radius: 0 10px 10px 0; border: 1.5px solid #cbd5e1; border-left: none;">
                                        unid.
                                    </span>
                                    @error('min_stock')
                                        <div class="invalid-feedback fw-medium">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 4: Descripción -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3 text-secondary pb-1 border-bottom">
                            <i class="bi bi-card-text text-primary"></i>
                            <span class="text-uppercase small fw-bold tracking-wide">4. Especificaciones Técnicas</span>
                        </div>

                        <div>
                            <label for="description" class="form-label-modern">
                                <span>Descripción / Características</span>
                            </label>
                            <textarea name="description"
                                      id="description"
                                      rows="3"
                                      class="form-control form-control-modern @error('description') is-invalid @enderror"
                                      placeholder="Conectividad, compatibilidad, switches, garantía, etc.">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback fw-medium">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="pt-3 border-top d-flex justify-content-end align-items-center gap-3">
                        <a href="{{ route('products.index') }}" class="btn-modern-secondary">
                            <i class="bi bi-arrow-left"></i>
                            <span>Cancelar</span>
                        </a>
                        <button type="submit" class="btn-modern-primary">
                            <i class="bi bi-check2-circle"></i>
                            <span>Guardar Producto</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
