<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Muestra el listado de proveedores.
     */
    public function index()
    {
        $suppliers = Supplier::withCount('products')->orderBy('id', 'asc')->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Muestra el formulario para crear un nuevo proveedor.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Almacena un nuevo proveedor en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'contact_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:255',
        ], [
            'name.required' => 'El nombre o razón social del proveedor es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Proveedor creado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un proveedor existente.
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Actualiza el proveedor en la base de datos.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'contact_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:255',
        ], [
            'name.required' => 'El nombre o razón social del proveedor es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Proveedor actualizado exitosamente.');
    }

    /**
     * Elimina un proveedor si no tiene productos asociados.
     */
    public function destroy(Supplier $supplier)
    {
        if ($supplier->products()->exists()) {
            return redirect()->route('suppliers.index')
                ->with('error', 'No se puede eliminar el proveedor porque tiene productos asociados.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Proveedor eliminado exitosamente.');
    }
}
