<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'price',
        'stock',
        'min_stock',
        'category_id',
        'supplier_id',
    ];

    /**
     * Obtiene la categoría a la que pertenece el producto.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Obtiene el proveedor que suministra el producto.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Obtiene el historial de movimientos de stock del producto.
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Determina si el producto se encuentra en alerta de stock bajo.
     */
    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }
}
