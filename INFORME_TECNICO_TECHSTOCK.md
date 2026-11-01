# Informe Técnico del Sistema TechStock
### Sistema de Inventario de Accesorios Informáticos
**Clasificación:** Documentación Técnica Académica  
**Versión:** 1.0.0  
**Fecha de elaboración:** 31 de octubre de 2026  
**Framework base:** Laravel 12 · PHP ^8.2 · SQLite · Tailwind CSS v4

---

## 1. Resumen Ejecutivo y Alcance del Sistema

### 1.1 Descripción General

**TechStock** es un sistema web de gestión de inventario especializado en el registro, control y trazabilidad de accesorios informáticos. Desarrollado sobre el framework **Laravel 12** con una arquitectura **MVC (Model–View–Controller)**, el sistema provee una interfaz centralizada para la administración integral del ciclo de vida del stock: desde el registro de productos y proveedores, hasta el seguimiento detallado de cada movimiento de inventario mediante un módulo de Kardex.

El sistema está orientado a medianas empresas del sector tecnológico que requieren visibilidad en tiempo real sobre el estado de su inventario, con alertas automáticas ante situaciones de stock crítico y un dashboard ejecutivo con métricas clave del negocio.

### 1.2 Alcance Funcional

El alcance del sistema comprende los siguientes dominios funcionales:

| Dominio | Descripción |
|---|---|
| **Autenticación** | Gestión de sesiones de usuario con mecanismo seguro de login/logout |
| **Catálogo de Productos** | CRUD completo de accesorios informáticos con código SKU único |
| **Categorías** | Agrupación taxonómica de productos con protección de integridad referencial |
| **Proveedores** | Registro de proveedores con datos de contacto y protección referencial |
| **Kardex / Movimientos** | Registro atómico de entradas y salidas de stock con trazabilidad completa |
| **Dashboard Ejecutivo** | Panel de métricas en tiempo real: valorización, alertas de stock crítico y distribución por categorías |

### 1.3 Usuarios del Sistema

El sistema está concebido para un único rol de usuario autenticado (administrador de inventario), quien dispone de acceso completo a todos los módulos funcionales tras iniciar sesión. Todas las rutas de gestión están protegidas por el middleware de autenticación nativo de Laravel (`auth`).

---

## 2. Arquitectura de Base de Datos y Modelos

### 2.1 Motor de Base de Datos

El sistema utiliza **SQLite** como motor de persistencia, configurado mediante el archivo `database/database.sqlite`. Esta elección responde a la naturaleza del proyecto (entorno académico/de desarrollo), eliminando la necesidad de un servidor de base de datos externo. Laravel gestiona la conexión a través de su capa de abstracción **Eloquent ORM**, lo que garantiza portabilidad hacia motores relacionales más robustos (MySQL, PostgreSQL) sin modificaciones en la lógica de negocio.

### 2.2 Esquema Relacional — Diagrama de Entidades

```
┌──────────────┐       ┌──────────────────┐       ┌──────────────────┐
│  categories  │       │     products      │       │    suppliers     │
├──────────────┤       ├──────────────────┤       ├──────────────────┤
│ id (PK)      │ 1   n │ id (PK)          │ n   1 │ id (PK)          │
│ name         │◄──────│ category_id (FK) │──────►│ name             │
│ description  │       │ supplier_id (FK) │       │ contact_name     │
│ created_at   │       │ name             │       │ phone            │
│ updated_at   │       │ code (UNIQUE)    │       │ email            │
└──────────────┘       │ description      │       │ address          │
                       │ price (10,2)     │       │ created_at       │
                       │ stock (int)      │       │ updated_at       │
                       │ min_stock (int)  │       └──────────────────┘
                       │ created_at       │
                       │ updated_at       │       ┌──────────────────────┐
                       └──────────────────┘       │   stock_movements    │
                                 │ 1              ├──────────────────────┤
                                 │                │ id (PK)              │
                                 │ n              │ product_id (FK)      │
                                 └───────────────►│ type (entrada/salida)│
                                                  │ quantity (uint)      │
                                                  │ stock_before (uint)  │
                                                  │ stock_after (uint)   │
                                                  │ reason (nullable)    │
                                                  │ created_at           │
                                                  │ updated_at           │
                                                  └──────────────────────┘
```

### 2.3 Descripción de Entidades

#### 2.3.1 Entidad `categories` (Categorías)

Representa la clasificación taxonómica de los accesorios informáticos.

| Columna | Tipo | Restricciones | Descripción |
|---|---|---|---|
| `id` | `INTEGER` | `PK`, `AUTO_INCREMENT` | Identificador único de la categoría |
| `name` | `VARCHAR(100)` | `NOT NULL`, `UNIQUE` | Nombre descriptivo de la categoría |
| `description` | `TEXT` | `NULLABLE` | Descripción extendida de la categoría |
| `created_at` | `TIMESTAMP` | — | Fecha de creación (automático) |
| `updated_at` | `TIMESTAMP` | — | Fecha de última modificación (automático) |

**Relaciones:** Una categoría puede contener múltiples productos (`HasMany → Product`). La eliminación está restringida si existen productos asociados (`restrictOnDelete`).

#### 2.3.2 Entidad `suppliers` (Proveedores)

Registra las empresas o personas que suministran los accesorios al inventario.

| Columna | Tipo | Restricciones | Descripción |
|---|---|---|---|
| `id` | `INTEGER` | `PK`, `AUTO_INCREMENT` | Identificador único del proveedor |
| `name` | `VARCHAR(150)` | `NOT NULL` | Razón social o nombre comercial |
| `contact_name` | `VARCHAR(100)` | `NULLABLE` | Nombre del contacto principal |
| `phone` | `VARCHAR(20)` | `NULLABLE` | Número de teléfono de contacto |
| `email` | `VARCHAR(100)` | `NULLABLE` | Correo electrónico de contacto |
| `address` | `VARCHAR(255)` | `NULLABLE` | Dirección física del proveedor |
| `created_at` | `TIMESTAMP` | — | Fecha de creación (automático) |
| `updated_at` | `TIMESTAMP` | — | Fecha de última modificación (automático) |

**Relaciones:** Un proveedor puede abastecer múltiples productos (`HasMany → Product`). La eliminación está bloqueada si tiene productos registrados.

#### 2.3.3 Entidad `products` (Productos)

Entidad central del sistema. Almacena el catálogo completo de accesorios informáticos.

| Columna | Tipo | Restricciones | Descripción |
|---|---|---|---|
| `id` | `INTEGER` | `PK`, `AUTO_INCREMENT` | Identificador único del producto |
| `name` | `VARCHAR(150)` | `NOT NULL` | Nombre descriptivo del accesorio |
| `code` | `VARCHAR(50)` | `NOT NULL`, `UNIQUE` | Código SKU único del producto |
| `description` | `TEXT` | `NULLABLE` | Descripción técnica detallada |
| `price` | `DECIMAL(10,2)` | `NOT NULL` | Precio unitario del producto |
| `stock` | `INTEGER` | `DEFAULT 0` | Cantidad actual en inventario |
| `min_stock` | `INTEGER` | `DEFAULT 5` | Umbral mínimo de alerta de stock crítico |
| `category_id` | `INTEGER` | `FK → categories.id` | Categoría a la que pertenece el producto |
| `supplier_id` | `INTEGER` | `FK → suppliers.id` | Proveedor que suministra el producto |
| `created_at` | `TIMESTAMP` | — | Fecha de registro (automático) |
| `updated_at` | `TIMESTAMP` | — | Fecha de última modificación (automático) |

**Relaciones:**
- `BelongsTo → Category`: Cada producto pertenece a una categoría.
- `BelongsTo → Supplier`: Cada producto está asociado a un proveedor.
- `HasMany → StockMovement`: Un producto puede registrar múltiples movimientos de Kardex.

**Método de negocio:** El modelo implementa el método `isLowStock(): bool`, que evalúa la condición `stock <= min_stock` para determinar si el producto se encuentra en estado de alerta.

#### 2.3.4 Entidad `stock_movements` (Movimientos de Stock / Kardex)

Implementa el registro de trazabilidad (Kardex) de todos los movimientos de inventario. Cada registro es **inmutable** por diseño, garantizando la auditoría completa del historial.

| Columna | Tipo | Restricciones | Descripción |
|---|---|---|---|
| `id` | `INTEGER` | `PK`, `AUTO_INCREMENT` | Identificador único del movimiento |
| `product_id` | `INTEGER` | `FK → products.id` | Producto al que corresponde el movimiento |
| `type` | `VARCHAR` | `NOT NULL`, `in: entrada,salida` | Tipo de movimiento |
| `quantity` | `UNSIGNED INT` | `NOT NULL` | Cantidad de unidades movidas |
| `stock_before` | `UNSIGNED INT` | `NOT NULL` | Stock del producto antes del movimiento |
| `stock_after` | `UNSIGNED INT` | `NOT NULL` | Stock del producto después del movimiento |
| `reason` | `VARCHAR(255)` | `NULLABLE` | Motivo o descripción del movimiento |
| `created_at` | `TIMESTAMP` | — | Fecha y hora exacta del movimiento |
| `updated_at` | `TIMESTAMP` | — | Fecha de última modificación |

**Relaciones:** `BelongsTo → Product`: Cada movimiento está vinculado a un único producto. La eliminación del producto está restringida si posee movimientos registrados.

### 2.4 Integridad Referencial y Restricciones

El esquema implementa **integridad referencial estricta** mediante la directiva `restrictOnDelete()` de Laravel en todas las claves foráneas. Esto implica que:

- No es posible eliminar una **categoría** si tiene productos asociados.
- No es posible eliminar un **proveedor** si tiene productos asociados.
- No es posible eliminar un **producto** si posee movimientos de stock registrados en el Kardex.

Esta restricción se aplica en dos niveles: a nivel de base de datos (foreign key constraints de SQLite) y a nivel de aplicación (validación explícita en cada método `destroy()` de los controladores correspondientes).

---

## 3. Descripción Funcional de los Módulos

### 3.1 Módulo de Autenticación

**Controlador:** [`AuthController`](file:///c:/Users/USER/taller03/app/Http/Controllers/AuthController.php)  
**Rutas:** `GET /login`, `POST /login`, `POST /logout`

El módulo de autenticación utiliza el sistema de autenticación nativo de Laravel (`Illuminate\Support\Facades\Auth`). Sus responsabilidades son:

- **`showLoginForm()`**: Renderiza el formulario de inicio de sesión. Si el usuario ya posee una sesión activa, redirige automáticamente al Dashboard.
- **`login()`**: Valida las credenciales (email/contraseña), regenera el token de sesión para prevenir ataques de fijación de sesión (`session()->regenerate()`), y soporta la opción *"Recordarme"* mediante cookie persistente.
- **`logout()`**: Invalida la sesión activa, regenera el token CSRF y redirige al formulario de login.

Todas las rutas de gestión del sistema (Dashboard, Productos, Categorías, Proveedores, Movimientos) están protegidas por el middleware `auth`, garantizando que únicamente usuarios autenticados puedan acceder a ellas.

---

### 3.2 Módulo Dashboard (Panel de Control Ejecutivo)

**Controlador:** [`DashboardController`](file:///c:/Users/USER/taller03/app/Http/Controllers/DashboardController.php)  
**Vista:** `resources/views/dashboard.blade.php`  
**Ruta:** `GET /dashboard`

El Dashboard es el módulo central de inteligencia operacional del sistema. A través de consultas directas a la base de datos mediante Eloquent ORM, computa y presenta las siguientes métricas en tiempo real:

#### 3.2.1 Métricas de Conteo Global

| Métrica | Implementación | Descripción |
|---|---|---|
| Total de Productos | `Product::count()` | Número total de accesorios registrados en el catálogo |
| Total de Categorías | `Category::count()` | Número de categorías activas en el sistema |
| Total de Proveedores | `Supplier::count()` | Número de proveedores registrados |
| Alertas de Stock Crítico | `whereColumn('stock', '<=', 'min_stock')` | Conteo de productos bajo su umbral mínimo |

#### 3.2.2 Valorización Total del Inventario

```php
$inventoryValue = Product::selectRaw('SUM(price * stock) as total_value')
    ->value('total_value') ?? 0;
```

Se calcula mediante una consulta SQL agregada que multiplica el precio unitario (`price`) por el stock disponible (`stock`) de cada producto, obteniendo la valorización total del inventario en términos monetarios. Esta métrica es fundamental para la toma de decisiones financieras.

#### 3.2.3 Control de Stock Crítico

```php
$lowStockProducts = Product::with(['category', 'supplier'])
    ->whereColumn('stock', '<=', 'min_stock')
    ->orderBy('stock')
    ->get();
```

El sistema identifica dinámicamente todos los productos cuyo stock actual es menor o igual al umbral mínimo configurado (`min_stock`), presentándolos ordenados de menor a mayor stock para priorizar la atención en los casos más urgentes. Esta consulta incluye eager loading de las relaciones `category` y `supplier` para evitar el problema N+1 en la renderización.

#### 3.2.4 Distribución por Categorías

```php
$categoryDistribution = Category::withCount('products')
    ->orderByDesc('products_count')
    ->get();
```

Genera un ranking de categorías según la cantidad de productos que contienen, útil para visualizar la composición del catálogo.

#### 3.2.5 Últimos Movimientos Registrados

```php
$recentMovements = StockMovement::with('product')
    ->latest()
    ->take(6)
    ->get();
```

Muestra los 6 movimientos de stock más recientes con eager loading del producto asociado, brindando visibilidad inmediata sobre la actividad reciente del inventario.

---

### 3.3 Módulo de Categorías

**Controlador:** [`CategoryController`](file:///c:/Users/USER/taller03/app/Http/Controllers/CategoryController.php)  
**Rutas:** `Resource /categories` (excluye `show`)

Implementa el CRUD completo de categorías mediante una ruta de tipo `resource`. Sus características técnicas incluyen:

- **Listado paginado** con conteo de productos asociados (`withCount('products')`) a 10 registros por página.
- **Validación en creación:** nombre único (`unique:categories,name`), longitud máxima de 100 caracteres.
- **Validación en edición:** unicidad excluyendo el registro actual (`Rule::unique()->ignore($id)`).
- **Protección de eliminación:** verificación explícita de productos dependientes antes de ejecutar el `DELETE`, con mensaje de error descriptivo al usuario.

---

### 3.4 Módulo de Proveedores

**Controlador:** [`SupplierController`](file:///c:/Users/USER/taller03/app/Http/Controllers/SupplierController.php)  
**Rutas:** `Resource /suppliers` (excluye `show`)

Gestiona el registro de proveedores de accesorios informáticos. Sus características técnicas incluyen:

- **Listado paginado** con conteo de productos vinculados (`withCount('products')`).
- **Validación de correo electrónico** con la regla `email` nativa de Laravel.
- **Protección de eliminación:** idéntica al módulo de categorías — impide la eliminación de proveedores con productos asociados.
- Los campos `contact_name`, `phone`, `email` y `address` son opcionales (`nullable`), contemplando casos en que la información de contacto no está disponible.

---

### 3.5 Módulo de Productos

**Controlador:** [`ProductController`](file:///c:/Users/USER/taller03/app/Http/Controllers/ProductController.php)  
**Rutas:** `Resource /products` (excluye `show`)

Es el módulo de mayor complejidad del sistema, al constituir la entidad central del inventario.

#### 3.5.1 Listado con Búsqueda y Filtros Combinados

El método `index()` implementa un sistema de filtrado dinámico acumulativo mediante construcción de consultas con Eloquent Query Builder:

| Parámetro | Tipo de Filtro | Implementación |
|---|---|---|
| `search` | Búsqueda por texto | `WHERE name LIKE %x% OR code LIKE %x%` |
| `category_id` | Filtro exacto | `WHERE category_id = x` |
| `supplier_id` | Filtro exacto | `WHERE supplier_id = x` |
| `stock_status` | Filtro condicional | `low`: `stock <= min_stock` / `normal`: `stock > min_stock` |

Los resultados se paginan a 10 registros por página con `withQueryString()`, que preserva los parámetros de filtrado en los enlaces de paginación.

#### 3.5.2 Validación de Negocio

Las reglas de validación aplicadas al crear/editar productos garantizan la consistencia de los datos:

- `code`: único a nivel de tabla (`unique:products,code`), con exclusión inteligente del registro actual en edición.
- `price`: numérico, mínimo 0 (no se permiten precios negativos).
- `stock` y `min_stock`: enteros no negativos.
- `category_id` y `supplier_id`: deben corresponder a registros existentes en sus respectivas tablas (`exists:table,id`).

#### 3.5.3 Protección de Integridad en Eliminación

```php
public function destroy(Product $product)
{
    if ($product->stockMovements()->exists()) {
        return redirect()->route('products.index')
            ->with('error', 'No se puede eliminar el producto porque registra 
                             movimientos de stock en el Kardex.');
    }
    $product->delete();
}
```

Antes de ejecutar la eliminación física del registro, el sistema verifica si el producto posee movimientos de stock registrados. En caso afirmativo, la operación es rechazada con un mensaje de error, preservando la integridad del historial de auditoría.

---

### 3.6 Módulo de Movimientos de Stock (Kardex)

**Controlador:** [`StockMovementController`](file:///c:/Users/USER/taller03/app/Http/Controllers/StockMovementController.php)  
**Rutas:** `GET /stock-movements`, `POST /stock-movements`

Este módulo implementa la trazabilidad completa del inventario, registrando cada variación de stock como un evento inmutable. Es el núcleo de la auditoría del sistema.

#### 3.6.1 Registro Atómico Mediante Transacciones de Base de Datos

El proceso de registro de un movimiento se encapsula íntegramente dentro de una **transacción de base de datos** (`DB::transaction()`), garantizando atomicidad (principio ACID):

```php
DB::transaction(function () use ($request) {
    $product = Product::where('id', $request->product_id)
        ->lockForUpdate()   // Bloqueo de fila para concurrencia segura
        ->firstOrFail();

    $stockBefore = (int) $product->stock;
    $quantity    = (int) $request->quantity;

    if ($request->type === 'salida') {
        if ($quantity > $stockBefore) {
            throw ValidationException::withMessages([...]);
        }
        $stockAfter = $stockBefore - $quantity;
    } else {
        $stockAfter = $stockBefore + $quantity;
    }

    StockMovement::create([...]);   // Registro de trazabilidad
    $product->update(['stock' => $stockAfter]); // Actualización de stock
});
```

**Aspectos técnicos destacados:**

| Característica | Descripción |
|---|---|
| **Atomicidad** | El registro del movimiento y la actualización del stock ocurren como una única operación indivisible. Si cualquiera falla, ambas operaciones son revertidas (`ROLLBACK`). |
| **Bloqueo de fila** (`lockForUpdate`) | Previene condiciones de carrera en escenarios de acceso concurrente, garantizando que el stock leído y modificado sea siempre el más actualizado. |
| **Validación de stock suficiente** | Para movimientos de tipo `salida`, el sistema verifica que la cantidad a descontar no supere el stock disponible, lanzando un `ValidationException` en caso contrario. |
| **Trazabilidad completa** | Se registran `stock_before` y `stock_after` en cada movimiento, permitiendo reconstruir el historial completo del inventario en cualquier punto temporal. |

#### 3.6.2 Tipos de Movimiento

| Tipo | Efecto sobre el Stock | Descripción |
|---|---|---|
| `entrada` | `stock_after = stock_before + quantity` | Recepción de mercancía, devoluciones, ajuste positivo |
| `salida` | `stock_after = stock_before - quantity` | Venta, despacho, pérdida, ajuste negativo |

---

## 4. Especificaciones Técnicas del Stack Tecnológico

### 4.1 Backend

| Componente | Tecnología | Versión | Función |
|---|---|---|---|
| **Lenguaje** | PHP | `^8.2` | Lenguaje de programación del servidor |
| **Framework** | Laravel | `^12.0` | Framework MVC principal |
| **ORM** | Eloquent ORM | (incluido en Laravel) | Mapeo objeto-relacional y gestión de consultas |
| **Motor DB** | SQLite | 3.x | Sistema de gestión de base de datos embebido |
| **CLI** | Artisan | (incluido en Laravel) | Herramienta de comandos para migraciones, seeders y gestión del proyecto |
| **REPL** | Laravel Tinker | `^2.10.1` | Consola interactiva para inspección y depuración |

#### 4.1.1 Arquitectura MVC de Laravel

Laravel implementa el patrón **Model–View–Controller** con las siguientes capas:

- **Models** (`app/Models/`): Clases Eloquent que encapsulan la lógica de negocio y las relaciones entre entidades.
- **Controllers** (`app/Http/Controllers/`): Clases que procesan las solicitudes HTTP, coordinan la lógica de aplicación y retornan respuestas.
- **Views** (`resources/views/`): Plantillas Blade que generan la capa de presentación HTML.
- **Routes** (`routes/web.php`): Definición centralizada del enrutamiento HTTP con agrupación por middleware.

#### 4.1.2 Características de Seguridad del Backend

- **Protección CSRF**: Token CSRF automático en todos los formularios, verificado por el middleware `VerifyCsrfToken`.
- **Validación servidor**: Todas las entradas del usuario son validadas mediante el sistema de `Form Request` de Laravel antes de ser procesadas o persistidas.
- **Binding implícito de modelos** (*Route Model Binding*): Laravel resuelve automáticamente las instancias de modelo a partir de los parámetros de ruta, lanzando un `404` si el registro no existe.
- **Hashing de contraseñas**: Las contraseñas de usuarios son almacenadas con `bcrypt` mediante el sistema de autenticación nativo de Laravel.
- **Regeneración de sesión**: Tras el inicio de sesión exitoso, el ID de sesión es regenerado (`session()->regenerate()`) para prevenir ataques de fijación de sesión.

### 4.2 Frontend

| Componente | Tecnología | Versión | Función |
|---|---|---|---|
| **Motor de Plantillas** | Blade (Laravel) | (incluido en Laravel) | Sistema de templating del servidor para generación de HTML |
| **Framework CSS** | Tailwind CSS | `^4.0.0` | Framework CSS de utilidad para estilos responsivos |
| **Plugin Tailwind** | @tailwindcss/vite | `^4.0.0` | Integración de Tailwind CSS 4 con Vite |
| **Bundler** | Vite | `^7.0.7` | Herramienta de construcción y servidor de desarrollo HMR |
| **Plugin Vite** | laravel-vite-plugin | `^2.0.0` | Integración oficial de Vite con Laravel |
| **HTTP Client** | Axios | `^1.11.0` | Librería para peticiones HTTP asíncronas (disponible) |

#### 4.2.1 Motor de Plantillas Blade

Las vistas del sistema están organizadas mediante el sistema de layouts y componentes de Blade:

```
resources/views/
├── layouts/          # Layout maestro con barra de navegación
├── auth/             # Formulario de inicio de sesión
├── dashboard.blade.php   # Panel de control ejecutivo
├── categories/       # CRUD de categorías (index, create, edit)
├── suppliers/        # CRUD de proveedores (index, create, edit)
├── products/         # CRUD de productos (index, create, edit)
└── stock_movements/  # Kardex y registro de movimientos (index)
```

#### 4.2.2 Pipeline de Assets con Vite

El sistema de construcción de activos utiliza **Vite 7** con el plugin oficial de Laravel, que provee:

- **Hot Module Replacement (HMR)** durante el desarrollo, con recarga instantánea de CSS y JavaScript.
- **Integración nativa de Tailwind CSS v4** mediante `@tailwindcss/vite`, que procesa las clases de utilidad directamente en el pipeline de Vite sin requerir un archivo de configuración `tailwind.config.js` separado.
- **Optimización para producción**: minificación de CSS y JS, y generación de manifesto de assets con hash para control de caché.

### 4.3 Herramientas de Desarrollo

| Herramienta | Versión | Propósito |
|---|---|---|
| **Laravel Pint** | `^1.24` | Formateador de código PHP (PSR-12) |
| **Laravel Sail** | `^1.41` | Entorno de desarrollo Docker |
| **Laravel Pail** | `^1.2.2` | Visualizador de logs en tiempo real |
| **PHPUnit** | `^11.5.50` | Framework de pruebas unitarias |
| **Faker PHP** | `^1.23` | Generación de datos ficticios para tests y seeders |
| **Mockery** | `^1.6` | Librería de mocking para pruebas |
| **Concurrently** | `^9.0.1` | Ejecución simultánea de múltiples procesos de desarrollo |
| **NunoMaduro Collision** | `^8.6` | Visualización mejorada de errores en consola |

### 4.4 Configuración de Conexión a Base de Datos

La conexión a SQLite se configura en el archivo `.env` mediante la variable `DB_CONNECTION=sqlite`, sin necesidad de especificar credenciales adicionales. El archivo de base de datos se ubica en `database/database.sqlite`. Laravel gestiona la creación del archivo automáticamente mediante el script `post-create-project-cmd` en `composer.json`:

```json
"@php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\""
```

### 4.5 Enrutamiento y Estructura de URLs

El sistema emplea una arquitectura RESTful con las siguientes rutas:

| Método HTTP | URI | Controlador@Método | Nombre de Ruta |
|---|---|---|---|
| `GET` | `/login` | `AuthController@showLoginForm` | `login` |
| `POST` | `/login` | `AuthController@login` | `login.submit` |
| `POST` | `/logout` | `AuthController@logout` | `logout` |
| `GET` | `/dashboard` | `DashboardController@index` | `dashboard` |
| `GET` | `/categories` | `CategoryController@index` | `categories.index` |
| `GET` | `/categories/create` | `CategoryController@create` | `categories.create` |
| `POST` | `/categories` | `CategoryController@store` | `categories.store` |
| `GET` | `/categories/{id}/edit` | `CategoryController@edit` | `categories.edit` |
| `PUT/PATCH` | `/categories/{id}` | `CategoryController@update` | `categories.update` |
| `DELETE` | `/categories/{id}` | `CategoryController@destroy` | `categories.destroy` |
| `GET` | `/suppliers` | `SupplierController@index` | `suppliers.index` |
| *(análogo a categories)* | — | — | — |
| `GET` | `/products` | `ProductController@index` | `products.index` |
| *(análogo a categories)* | — | — | — |
| `GET` | `/stock-movements` | `StockMovementController@index` | `stock-movements.index` |
| `POST` | `/stock-movements` | `StockMovementController@store` | `stock-movements.store` |

> [!NOTE]
> Las rutas de tipo `resource` para `categories`, `suppliers` y `products` son registradas automáticamente por Laravel excluyendo el método `show`, ya que el sistema no implementa vistas de detalle individual de registros.

---

## 5. Consideraciones de Diseño y Patrones Aplicados

### 5.1 Patrones de Software Identificados

| Patrón | Aplicación en el Proyecto |
|---|---|
| **MVC** | Separación estricta entre Modelos (Eloquent), Vistas (Blade) y Controladores |
| **Repository implícito** | Eloquent ORM abstrae el acceso a datos, desacoplando la lógica de negocio de las consultas SQL |
| **Unit of Work** | `DB::transaction()` en `StockMovementController` garantiza consistencia en operaciones multi-paso |
| **Active Record** | Los modelos Eloquent encapsulan tanto los datos como las operaciones de persistencia |
| **Route Model Binding** | Resolución automática de instancias de modelo desde parámetros de ruta |
| **Eager Loading** | Uso de `with(['relation'])` para prevenir el problema de consultas N+1 |

### 5.2 Principios de Integridad Aplicados

- **Integridad referencial**: Restricciones de clave foránea a nivel de base de datos y validación a nivel de aplicación.
- **Unicidad de datos**: El código SKU de productos y el nombre de categorías son únicos a nivel de base de datos.
- **Inmutabilidad del Kardex**: Los movimientos de stock no pueden ser editados ni eliminados directamente desde la interfaz, garantizando un historial de auditoría inviolable.
- **Consistencia transaccional**: Las operaciones de actualización de stock son atómicas, evitando estados inconsistentes ante fallos parciales.

---

*Documento generado automáticamente a partir del análisis del código fuente del proyecto TechStock. Versión del informe: 1.0.0.*
