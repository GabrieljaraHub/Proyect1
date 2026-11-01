<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Usuario Administrador (Gabriel Tito Jara Ccoyso)
        User::updateOrCreate(
            ['email' => 'admin@ejemplo.com'],
            [
                'name' => 'Gabriel Tito Jara Ccoyso',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Categorías
        $catPerifericos = Category::firstOrCreate(
            ['name' => 'Periféricos e Input'],
            ['description' => 'Teclados, mouses, webcams y auriculares para oficina y gaming']
        );

        $catAlmacenamiento = Category::firstOrCreate(
            ['name' => 'Almacenamiento y Memorias'],
            ['description' => 'Discos duros externos, SSD NVMe, memorias USB y tarjetas SD']
        );

        $catCables = Category::firstOrCreate(
            ['name' => 'Cables y Conectividad'],
            ['description' => 'Cables HDMI, adaptadores USB-C, hubs y cables de red Ethernet']
        );

        $catMonitores = Category::firstOrCreate(
            ['name' => 'Monitores y Video'],
            ['description' => 'Monitores gamers, pantallas portátiles, soportes y convertidores de video']
        );

        // 3. Proveedores
        $supTech = Supplier::firstOrCreate(
            ['name' => 'Tech Perú Mayorista S.A.C.'],
            [
                'contact_name' => 'Carlos Mendoza',
                'phone' => '+51 987 654 321',
                'email' => 'ventas@techperu.com',
                'address' => 'Av. República de Panamá 3500, San Isidro, Lima',
            ]
        );

        $supLogi = Supplier::firstOrCreate(
            ['name' => 'Importaciones Computec E.I.R.L.'],
            [
                'contact_name' => 'Mariana Rojas',
                'phone' => '+51 912 345 678',
                'email' => 'contacto@computec.com.pe',
                'address' => 'Av. Garcilaso de la Vega 1348, Int. 201, Cercado de Lima',
            ]
        );

        // 4. Productos
        $p1 = Product::updateOrCreate(
            ['code' => 'ACC-TEC-001'],
            [
                'name' => 'Teclado Mecánico RGB Switch Red',
                'description' => 'Teclado mecánico con iluminación RGB programable y switch lineal silencioso',
                'price' => 149.90,
                'stock' => 20,
                'min_stock' => 5,
                'category_id' => $catPerifericos->id,
                'supplier_id' => $supTech->id,
            ]
        );

        $p2 = Product::updateOrCreate(
            ['code' => 'ACC-MOU-002'],
            [
                'name' => 'Mouse Ergonómico Inalámbrico 2.4GHz',
                'description' => 'Mouse ergonómico vertical para evitar lesiones en la muñeca, batería recargable',
                'price' => 75.50,
                'stock' => 12,
                'min_stock' => 5,
                'category_id' => $catPerifericos->id,
                'supplier_id' => $supLogi->id,
            ]
        );

        $p3 = Product::updateOrCreate(
            ['code' => 'ACC-SSD-003'],
            [
                'name' => 'Disco Sólido Externo SSD 1TB USB 3.2',
                'description' => 'Velocidad de transferencia de hasta 1050 MB/s, resistente a caídas',
                'price' => 320.00,
                'stock' => 4, // Alerta: stock bajo
                'min_stock' => 5,
                'category_id' => $catAlmacenamiento->id,
                'supplier_id' => $supTech->id,
            ]
        );

        $p4 = Product::updateOrCreate(
            ['code' => 'ACC-HUB-004'],
            [
                'name' => 'Hub USB-C 7 en 1 multipuerto HDMI 4K',
                'description' => 'Adaptador con puerto HDMI 4K@60Hz, 3 puertos USB 3.0, lector SD/TF y PD 100W',
                'price' => 89.00,
                'stock' => 15,
                'min_stock' => 5,
                'category_id' => $catCables->id,
                'supplier_id' => $supLogi->id,
            ]
        );

        // 5. Movimientos de Stock Iniciales
        if (StockMovement::count() === 0) {
            StockMovement::create([
                'product_id' => $p1->id,
                'type' => 'entrada',
                'quantity' => 25,
                'stock_before' => 0,
                'stock_after' => 25,
                'reason' => 'Compra inicial a proveedor',
                'created_at' => now()->subDays(3)->setTime(9, 30),
            ]);

            StockMovement::create([
                'product_id' => $p1->id,
                'type' => 'salida',
                'quantity' => 5,
                'stock_before' => 25,
                'stock_after' => 20,
                'reason' => 'Venta a cliente institucional',
                'created_at' => now()->subDays(2)->setTime(14, 15),
            ]);

            StockMovement::create([
                'product_id' => $p2->id,
                'type' => 'entrada',
                'quantity' => 15,
                'stock_before' => 0,
                'stock_after' => 15,
                'reason' => 'Compra a distribuidor Logitech',
                'created_at' => now()->subDays(2)->setTime(10, 0),
            ]);

            StockMovement::create([
                'product_id' => $p2->id,
                'type' => 'salida',
                'quantity' => 3,
                'stock_before' => 15,
                'stock_after' => 12,
                'reason' => 'Venta en mostrador',
                'created_at' => now()->subDay()->setTime(16, 45),
            ]);

            StockMovement::create([
                'product_id' => $p3->id,
                'type' => 'entrada',
                'quantity' => 6,
                'stock_before' => 0,
                'stock_after' => 6,
                'reason' => 'Ingreso por lote promocional',
                'created_at' => now()->subDays(4)->setTime(11, 20),
            ]);

            StockMovement::create([
                'product_id' => $p3->id,
                'type' => 'salida',
                'quantity' => 2,
                'stock_before' => 6,
                'stock_after' => 4,
                'reason' => 'Venta online web',
                'created_at' => now()->subHours(5)->setTime(12, 10),
            ]);
        }
    }
}
