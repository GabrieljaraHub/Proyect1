<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockMovementController extends Controller
{
    /**
     * Muestra el historial de movimientos de stock y la lista de productos para registrar nuevos.
     */
    public function index()
    {
        $movements = StockMovement::with('product')
            ->latest()
            ->paginate(10);

        $products = Product::orderBy('name')->get();

        return view('stock_movements.index', compact('movements', 'products'));
    }

    /**
     * Registra un nuevo movimiento de stock (Entrada o Salida) de forma atómica y consistente.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:entrada,salida',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ], [
            'product_id.required' => 'Debe seleccionar un producto.',
            'product_id.exists' => 'El producto seleccionado no existe en el catálogo.',
            'type.required' => 'Debe indicar el tipo de movimiento.',
            'type.in' => 'El tipo de movimiento debe ser entrada o salida.',
            'quantity.required' => 'La cantidad es obligatoria.',
            'quantity.integer' => 'La cantidad debe ser un número entero.',
            'quantity.min' => 'La cantidad a mover debe ser de al menos 1 unidad.',
            'reason.max' => 'El motivo no debe superar los 255 caracteres.',
        ]);

        DB::transaction(function () use ($request) {
            // Bloqueo de fila para concurrencia segura
            $product = Product::where('id', $request->product_id)
                ->lockForUpdate()
                ->firstOrFail();

            $stockBefore = (int) $product->stock;
            $quantity = (int) $request->quantity;

            if ($request->type === 'salida') {
                if ($quantity > $stockBefore) {
                    throw ValidationException::withMessages([
                        'quantity' => "Stock insuficiente. El producto '{$product->name}' cuenta únicamente con {$stockBefore} unidad(es) disponible(s).",
                    ]);
                }
                $stockAfter = $stockBefore - $quantity;
            } else {
                $stockAfter = $stockBefore + $quantity;
            }

            // Registrar trazabilidad del movimiento
            StockMovement::create([
                'product_id' => $product->id,
                'type' => $request->type,
                'quantity' => $quantity,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reason' => $request->reason,
            ]);

            // Actualizar stock del producto
            $product->update([
                'stock' => $stockAfter,
            ]);
        });

        $tipoTexto = $request->type === 'entrada' ? 'Entrada' : 'Salida';

        return redirect()->route('stock-movements.index')
            ->with('success', "Movimiento de {$tipoTexto} registrado con éxito. Stock de inventario actualizado.");
    }
}
