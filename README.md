# Gonvill: tienda en línea de libros

Tienda en línea de libros inspirada en la librería Gonvill, desarrollada en equipo como proyecto escolar en la Facultad de Informática de la UAS. Incluye el sitio web completo (catálogo, carrito, pagos, facturas y perfil de usuario), un panel de administración y una API REST.

> Proyecto académico sin relación con Gonvill. Las marcas e imágenes de libros se usan solo con fines educativos.

## Funcionalidades

**Clientes**
- Catálogo de libros con categorías, autores, búsqueda, novedades y destacados.
- Carrito de compras persistente, con cupones de descuento.
- Checkout con direcciones guardadas y métodos de envío.
- Pagos con **Stripe** y **PayPal**.
- Facturas en **PDF** generadas con dompdf, consultables desde el perfil.
- Historial de pedidos, lista de deseos y reseñas de libros.
- Registro con correo de bienvenida enviado **en segundo plano** (colas en Redis).
- Recuperación de contraseña por correo.

**Administración**
- Panel protegido por middleware de administrador.
- CRUD de libros.

**API REST** (Laravel Sanctum)
- Autenticación por token: registro, login, logout y usuario actual.
- Libros, categorías, carrito, cupones, pedidos, direcciones y métodos de envío.

## Stack

| Capa | Tecnología |
|------|------------|
| Backend | PHP 8.2, Laravel 12, Laravel Sanctum |
| Base de datos | PostgreSQL 15 |
| Caché, sesiones y colas | Redis 7 (predis) |
| Vistas | Blade |
| Pagos | Stripe, PayPal |
| PDF | barryvdh/laravel-dompdf |
| Infraestructura | Docker Compose: app (PHP-FPM), Nginx, PostgreSQL, Redis y un worker de colas |

## Estructura

```
app/
├── Http/Controllers/
│   ├── Web/    Controladores del sitio (vistas Blade, sesión)
│   └── Api/    Controladores de la API REST (tokens Sanctum)
├── Http/Middleware/AdminMiddleware.php
├── Mail/       Correos (bienvenida)
└── Models/     Book, Author, Category, Cart, Order, Payment, Invoice, Coupon, Review…
database/
├── migrations/
└── seeders/BooksTableSeeder.php   Catálogo de ejemplo
docker/nginx/default.conf
docker-compose.yml
```

## Cómo correrlo con Docker

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed --class=BooksTableSeeder
```

El sitio queda en http://localhost:8000. `docker-compose.yml` ya configura la conexión a PostgreSQL y Redis para los contenedores.

Para probar pagos y correos, agrega a `.env` tus llaves de prueba:

```env
STRIPE_KEY=
STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=
PAYPAL_CLIENT_ID=
PAYPAL_SECRET=
PAYPAL_MODE=sandbox
QUEUE_CONNECTION=redis
```

## Equipo

- **Raúl López** ([@JRLR22](https://github.com/JRLR22))
- **Kazandra Moreno** ([@kamoreno21](https://github.com/kamoreno21))
- **Tláloc Torres**
