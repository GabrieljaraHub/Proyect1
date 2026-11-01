<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Muestra el listado de productos de accesorios informáticos con búsqueda y filtros.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'supplier']);

        // Búsqueda por nombre o código SKU
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filtro por categoría
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Filtro por proveedor
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->input('supplier_id'));
        }

        // Filtro por estado de stock
        if ($request->filled('stock_status')) {
            if ($request->input('stock_status') === 'low') {
                $query->whereColumn('stock', '<=', 'min_stock');
            } elseif ($request->input('stock_status') === 'normal') {
                $query->whereColumn('stock', '>', 'min_stock');
            }
        }

        $products = $query->orderBy('id', 'asc')->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'suppliers'));
    }

    /**
     * Muestra el formulario para registrar un nuevo producto.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * Almacena un nuevo producto en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:50|unique:products,code',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
        ], [
            'name.required' => 'El nombre del accesorio/producto es obligatorio.',
            'code.required' => 'El código/SKU es obligatorio.',
            'code.unique' => 'Ya existe un producto registrado con este código.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un valor numérico.',
            'stock.required' => 'El stock inicial es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'min_stock.required' => 'El stock mínimo de alerta es obligatorio.',
            'min_stock.integer' => 'El stock mínimo debe ser un número entero.',
            'category_id.required' => 'Debe seleccionar una categoría.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
            'supplier_id.required' => 'Debe seleccionar un proveedor.',
            'supplier_id.exists' => 'El proveedor seleccionado no es válido.',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Producto registrado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un producto existente.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Actualiza el producto en la base de datos.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'code')->ignore($product->id),
            ],
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
        ], [
            'name.required' => 'El nombre del accesorio/producto es obligatorio.',
            'code.required' => 'El código/SKU es obligatorio.',
            'code.unique' => 'Ya existe un producto registrado con este código.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un valor numérico.',
            'stock.required' => 'El stock es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'min_stock.required' => 'El stock mínimo de alerta es obligatorio.',
            'min_stock.integer' => 'El stock mínimo debe ser un número entero.',
            'category_id.required' => 'Debe seleccionar una categoría.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
            'supplier_id.required' => 'Debe seleccionar un proveedor.',
            'supplier_id.exists' => 'El proveedor seleccionado no es válido.',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Elimina el producto de la base de datos protegiendo la integridad de movimientos.
     */
    public function destroy(Product $product)
    {
        if ($product->stockMovements()->exists()) {
            return redirect()->route('products.index')
                ->with('error', 'No se puede eliminar el producto porque registra movimientos de stock en el Kardex.');
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }
}
