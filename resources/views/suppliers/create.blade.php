@extends('layouts.app')

@section('title', 'Nuevo Proveedor')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="bi bi-truck me-1 text-primary"></i> Registrar Nuevo Proveedor
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf

                    <!-- Razón Social / Nombre -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nombre o Razón Social <span class="text-danger">*</span></label>
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="Ej. Tech Perú Distribuciones S.A.C."
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- Persona de Contacto -->
                        <div class="col-md-6 mb-3">
                            <label for="contact_name" class="form-label fw-semibold">Persona de Contacto</label>
                            <input type="text"
                                   name="contact_name"
                                   id="contact_name"
                                   class="form-control @error('contact_name') is-invalid @enderror"
                                   value="{{ old('contact_name') }}"
                                   placeholder="Ej. Juan Pérez">
                            @error('contact_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label fw-semibold">Teléfono</label>
                            <input type="text"
                                   name="phone"
                                   id="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}"
                                   placeholder="Ej. +51 987654321">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
                        <input type="email"
                               name="email"
                               id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="ventas@techperu.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Dirección -->
                    <div class="mb-4">
                        <label for="address" class="form-label fw-semibold">Dirección</label>
                        <input type="text"
                               name="address"
                               id="address"
                               class="form-control @error('address') is-invalid @enderror"
                               value="{{ old('address') }}"
                               placeholder="Av. Las Begonias 450, San Isidro, Lima">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Botones de Acción -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Guardar Proveedor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
