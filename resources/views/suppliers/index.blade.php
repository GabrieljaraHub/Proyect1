@extends('layouts.app')

@section('title', 'Gestión de Proveedores')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2">
            <h1 class="h3 fw-bold mb-0 text-dark">Proveedores y Distribuidores</h1>
            <span class="badge badge-soft-indigo rounded-pill px-3">{{ $suppliers->total() }} registrados</span>
        </div>
        <p class="text-muted small mb-0 mt-1">Directorio de mayoristas, distribuidores y contactos comerciales</p>
    </div>
    <a href="{{ route('suppliers.create') }}" class="btn-modern-primary">
        <i class="bi bi-plus-circle-fill"></i>
        <span>Nuevo Proveedor</span>
    </a>
</div>

<div class="card-modern">
    <div class="p-3 px-4 border-bottom bg-white d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-truck text-primary"></i>
            <span class="fw-bold text-dark">Empresas Proveedoras</span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-modern align-middle">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Razón Social / Empresa</th>
                    <th>Contacto Directo</th>
                    <th>Teléfono</th>
                    <th>Correo Electrónico</th>
                    <th class="text-center">Suministros</th>
                    <th class="text-end pe-4" style="width: 130px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                    <tr>
                        <td><span class="text-muted fw-semibold">#{{ $supplier->id }}</span></td>
                        <td>
                            <div class="fw-bold text-dark">{{ $supplier->name }}</div>
                            @if($supplier->address)
                                <div class="text-muted small"><i class="bi bi-geo-alt me-1 text-primary"></i>{{ $supplier->address }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="text-dark">{{ $supplier->contact_name ?? '—' }}</span>
                        </td>
                        <td>
                            @if($supplier->phone)
                                <span class="badge badge-soft-slate"><i class="bi bi-telephone me-1"></i>{{ $supplier->phone }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($supplier->email)
                                <a href="mailto:{{ $supplier->email }}" class="text-decoration-none fw-medium text-primary small">
                                    <i class="bi bi-envelope me-1"></i>{{ $supplier->email }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge badge-soft-indigo">
                                <i class="bi bi-box-seam me-1"></i>
                                {{ $supplier->products_count }} {{ Str::plural('producto', $supplier->products_count) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex align-items-center gap-1">
                                <a href="{{ route('suppliers.edit', $supplier) }}" class="btn-action-icon btn-action-edit" title="Editar">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este proveedor?');">
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
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                            No hay proveedores registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($suppliers->hasPages())
        <div class="p-3 px-4 border-top bg-white">
            {{ $suppliers->links() }}
        </div>
    @endif
</div>
@endsection
