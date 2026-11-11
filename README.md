# TEC-SGI 2026

Sistema de Gestión de Incidencias del instituto. Centraliza la mesa de ayuda, el inventario tecnológico, la base de conocimientos y los reportes de dirección en una sola plataforma.

## Características principales

- Gestión de tickets con creación, asignación, estados, comentarios e historial.
- Inventario de equipos con ubicaciones, responsables, especificaciones y estados.
- Base de conocimientos con artículos publicados y borradores.
- Reportes y estadísticas para administración y dirección.
- Gestión de usuarios y roles: `admin`, `tecnico`, `usuario` y `directivo`.
- Interfaz responsive con Livewire, Alpine.js y Tailwind CSS.

## Stack tecnológico

- **Backend:** Laravel 13
- **Interfaz reactiva:** Livewire 3
- **Base de datos:** MariaDB
- **Estilos:** Tailwind CSS 3
- **Interacciones:** Alpine.js
- **Compilación de assets:** Vite
- **Pruebas:** Pest

## Requisitos del servidor

- PHP 8.3 o superior (el proyecto fue probado con PHP 8.5.9).
- Composer 2.
- Node.js y npm.
- MariaDB o MySQL.
- Extensiones PHP habituales de Laravel: `mbstring`, `openssl`, `pdo`, `pdo_mysql`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath` y `fileinfo`.

## Instalación en el servidor del instituto

### 1. Colocar el proyecto

Sube el proyecto al directorio del servidor y entra en la carpeta raíz:

```bash
cd /ruta/al/proyecto/TEC-SGI-2026
```

### 2. Instalar dependencias de producción

```bash
composer install --optimize-autoloader --no-dev
```

### 3. Configurar las variables de entorno

```bash
cp .env.example .env
```

Edita `.env` y configura, como mínimo:

```env
APP_NAME="TEC-SGI 2026"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio-institucional.edu

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tec_sgi_db
DB_USERNAME=usuario_bd
DB_PASSWORD=contraseña_segura
```

Genera la clave de la aplicación:

```bash
php artisan key:generate
```

### 4. Crear y preparar la base de datos

Crea la base de datos en MariaDB y luego ejecuta las migraciones con los datos iniciales:

```bash
php artisan migrate --seed --force
```

> Importante: no uses `migrate:fresh` en el servidor de producción porque elimina todos los datos existentes.

### 5. Compilar los assets

```bash
npm install && npm run build
```

### 6. Optimizar Laravel para producción

```bash
php artisan optimize
```

### 7. Permisos de escritura

Asegura que el usuario del servidor web tenga permisos sobre las carpetas de almacenamiento y caché:

```bash
chmod -R 775 storage bootstrap/cache
```

### 8. Configurar el document root

El servidor web debe apuntar al directorio `public` del proyecto:

```text
/ruta/al/proyecto/TEC-SGI-2026/public
```

No expongas directamente la carpeta raíz del proyecto.

## Comandos útiles de mantenimiento

Limpiar todas las cachés:

```bash
php artisan optimize:clear
```

Recompilar los assets en desarrollo:

```bash
npm run dev
```

Ejecutar la suite de pruebas:

```bash
php artisan test
```

## Créditos

Desarrollado por **Marcos** y **Andy**.

Proyecto académico e institucional: **TEC-SGI 2026**.
