<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Muestra el panel de control principal (Dashboard) con estadísticas en tiempo real.
     */
    public function index()
    {
        // Conteos generales
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();

        // Productos en alerta de stock crítico (stock <= min_stock)
        $lowStockProducts = Product::with(['category', 'supplier'])
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock')
            ->get();
        $lowStockCount = $lowStockProducts->count();

        // Valorización total del inventario (precio x stock)
        $inventoryValue = Product::selectRaw('SUM(price * stock) as total_value')->value('total_value') ?? 0;

        // Distribución de productos por categorías
        $categoryDistribution = Category::withCount('products')
            ->orderByDesc('products_count')
            ->get();

        // Últimos movimientos de stock registrados
        $recentMovements = StockMovement::with('product')
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'lowStockCount',
            'lowStockProducts',
            'inventoryValue',
            'categoryDistribution',
            'recentMovements'
        ));
    }
}
