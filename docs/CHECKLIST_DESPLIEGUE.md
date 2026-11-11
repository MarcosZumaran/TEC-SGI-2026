# Checklist de Despliegue — TEC-SGI 2026

**Sistema de Gestión de Incidencias del Instituto TEC**

Esta guía resume los pasos para publicar TEC-SGI 2026 en el hosting real del instituto. Marca cada tarea al completarla.

> **Antes de comenzar:** realiza el despliegue en una ventana de mantenimiento, avisa a los usuarios y conserva un respaldo de la base de datos y de los archivos actuales.

---

## 1. Requisitos previos

Confirma que el servidor del instituto cumple con lo siguiente:

- [ ] **PHP 8.2 o superior** (el proyecto fue desarrollado con PHP 8.5).
- [ ] Extensiones PHP habilitadas: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml` y `zip`.
- [ ] **MariaDB 10.4 o superior** (o MySQL compatible).
- [ ] **Composer 2.x**.
- [ ] **Node.js 18 o superior** y **npm** para compilar los assets.
- [ ] Servidor web Apache o Nginx con el **document root apuntando a la carpeta `public/`**.
- [ ] Certificado **HTTPS** activo.
- [ ] Acceso a **cron** para tareas programadas.
- [ ] Comandos `zip` y `mysqldump` (o `mariadb-dump`) disponibles.
- [ ] Espacio en disco suficiente para respaldos.

Comandos de verificación:

```bash
php -v
php -m
composer --version
node -v
npm -v
mysql --version
command -v zip
command -v mysqldump
```

Si `mysqldump` no está disponible pero sí `mariadb-dump`, el script de respaldo puede ejecutarse así:

```bash
MYSQLDUMP_BIN=mariadb-dump ./scripts/backup.sh
```

---

## 2. Subida de archivos

### Opción A: FTP o panel de archivos del hosting

- [ ] Sube el proyecto al servidor, preferiblemente fuera de la carpeta pública del sitio.
- [ ] No subas `node_modules/` ni archivos temporales.
- [ ] No subas el archivo `.env` local; se creará uno nuevo en el servidor.
- [ ] Si el hosting no permite ejecutar Composer, sube también la carpeta `vendor/`.
- [ ] Verifica que la carpeta `public/` sea el document root del dominio o subdominio.
- [ ] Asegura permisos de escritura para `storage/` y `bootstrap/cache/`.

### Opción B: Git, si el instituto ya utiliza un repositorio

> No es necesario inicializar Git para este despliegue. Usa esta opción solo si el área de TI ya tiene un repositorio configurado.

- [ ] Clona el repositorio en el servidor.
- [ ] Ejecuta `composer install --optimize-autoloader --no-dev`.
- [ ] Continúa con la configuración del `.env`.

### Permisos recomendados

Reemplaza `www-data` por el usuario del servidor web si es diferente:

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R ug+rwX storage bootstrap/cache
```

---

## 3. Configuración del `.env` para producción

- [ ] Crea el archivo `.env` a partir del ejemplo:

```bash
cp .env.example .env
```

- [ ] Genera la clave de la aplicación (solo en la primera instalación):

```bash
php artisan key:generate
```

- [ ] Edita `.env` con los valores de producción:

```dotenv
APP_NAME="TEC-SGI 2026"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://sgi.tec.edu.pe

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tec_sgi_db
DB_USERNAME=usuario_bd
DB_PASSWORD=clave_segura

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

SESSION_SECURE_COOKIE=true
```

- [ ] Verifica que `APP_DEBUG` esté en `false`.
- [ ] Verifica que `APP_URL` use `https://` y el dominio real.
- [ ] No publiques ni compartas el archivo `.env`.
- [ ] Si el hosting no usa HTTPS, no actives `SESSION_SECURE_COOKIE` hasta contar con certificado.

> **Importante:** no reutilices las credenciales de desarrollo. Crea un usuario de base de datos con permisos suficientes para la aplicación, pero sin privilegios innecesarios.

---

## 4. Ejecución de migraciones y seeders

- [ ] Ejecuta las migraciones:

```bash
php artisan migrate --force
```

- [ ] En la primera instalación, ejecuta los seeders:

```bash
php artisan db:seed --force
```

También puedes ejecutar ambos pasos juntos en la primera instalación:

```bash
php artisan migrate --seed --force
```

- [ ] Si se ejecutó `UserSeeder`, cambia de inmediato las contraseñas de las cuentas de prueba. Las cuentas creadas son:
  - `admin@tec.edu.pe`
  - `tecnico@tec.edu.pe`
  - `directivo@tec.edu.pe`
- [ ] Si no se necesitan datos de demostración, no ejecutes los seeders o revisa antes qué registros crean.
- [ ] Nunca uses `migrate:fresh` ni `db:wipe` en producción: eliminan todos los datos.

---

## 5. Compilación de assets

En el servidor, si tiene Node.js:

```bash
npm ci
npm run build
```

Si el servidor no tiene Node.js, compila en un equipo de confianza y sube la carpeta `public/build/` generada:

```bash
npm ci
npm run build
```

- [ ] Verifica que exista `public/build/manifest.json`.
- [ ] Verifica que el sitio cargue los estilos y scripts sin errores 404.
- [ ] Después del build, puedes eliminar `node_modules/` del servidor si no lo necesitas.

---

## 6. Configuración de tareas programadas (Cron)

- [ ] Da permisos de ejecución al script de respaldo:

```bash
chmod +x scripts/backup.sh
```

- [ ] Abre el crontab del usuario del servidor web:

```bash
crontab -e
```

- [ ] Agrega el planificador de Laravel y el respaldo diario:

```cron
# Planificador de Laravel: se ejecuta cada minuto
* * * * * cd /var/www/tec-sgi && php artisan schedule:run >> /dev/null 2>&1

# Respaldo diario de TEC-SGI 2026 a las 23:00
0 23 * * * cd /var/www/tec-sgi && ./scripts/backup.sh >> storage/logs/backup.log 2>&1
```

- [ ] Reemplaza `/var/www/tec-sgi` por la ruta real del proyecto.
- [ ] Confirma que el usuario del cron tenga permisos sobre `storage/` y `backups/`.
- [ ] Ejecuta una prueba manual del respaldo:

```bash
./scripts/backup.sh
```

- [ ] Verifica que se cree un archivo `.zip` en `backups/`.
- [ ] Copia periódicamente los respaldos a un servidor o almacenamiento externo.

---

## 7. Pruebas de humo post-despliegue

Realiza estas pruebas en el navegador, con el sitio en producción:

### Acceso y navegación

- [ ] La página de inicio carga correctamente y muestra el branding de TEC-SGI 2026.
- [ ] El inicio de sesión funciona.
- [ ] El menú lateral y el menú móvil responden correctamente.
- [ ] Los estilos y el logo cargan sin errores 404.
- [ ] El cierre de sesión funciona.

### Flujo de tickets

- [ ] Un usuario puede crear un ticket con ubicación, categoría y descripción.
- [ ] El número de ticket se genera con el formato `TKT-AÑO-####`.
- [ ] El usuario puede consultar su ticket.
- [ ] Un técnico puede asignarse un ticket.
- [ ] El técnico puede cambiar el estado a **En Progreso**, **En Espera** y **Resuelto**.
- [ ] Se registra el historial de estados.
- [ ] Se pueden agregar comentarios públicos y notas internas.
- [ ] Al resolver un ticket, se actualiza la fecha de resolución.

### Inventario y conocimiento

- [ ] Un técnico o administrador puede crear un equipo en el inventario.
- [ ] Se pueden registrar las especificaciones de una PC o laptop.
- [ ] El código de activo duplicado es rechazado.
- [ ] Un técnico puede publicar un artículo de la Base de Conocimientos.
- [ ] Un usuario solo ve artículos publicados.
- [ ] Un usuario no puede acceder a las pantallas de administración.

### Usuarios y reportes

- [ ] Un administrador puede crear un usuario y asignarle un rol.
- [ ] Un administrador puede editar un usuario sin cambiar su contraseña.
- [ ] Un directivo puede abrir **Reportes**.
- [ ] Un administrador puede abrir **Reportes**.
- [ ] Un técnico recibe error 403 al intentar abrir reportes.
- [ ] Los gráficos de estados y categorías se muestran correctamente.
- [ ] Los indicadores de tickets del mes, promedio de resolución y tickets resueltos coinciden con los datos de prueba.

### Revisión técnica final

- [ ] Revisa `storage/logs/laravel.log` y confirma que no haya errores críticos.
- [ ] Confirma que `APP_DEBUG=false` en producción.
- [ ] Confirma que `.env` no sea accesible desde el navegador.
- [ ] Confirma que la base de datos no esté expuesta públicamente.
- [ ] Confirma que el certificado HTTPS esté activo.
- [ ] Ejecuta una prueba de restauración del respaldo en un entorno de pruebas.

---

## Cierre del despliegue

- [ ] Avisa a los usuarios que el sistema está disponible.
- [ ] Entrega las credenciales por un canal seguro.
- [ ] Exige el cambio de contraseñas iniciales.
- [ ] Registra la fecha, hora y responsable del despliegue.
- [ ] Conserva el respaldo previo al despliegue.
- [ ] Programa una revisión de logs y respaldos durante la primera semana.

### Comandos finales de verificación

```bash
php artisan optimize
php artisan test
```

Si se modificó la configuración o los assets y algo no se refleja:

```bash
php artisan optimize:clear
npm run build
php artisan optimize
```

> **Nota:** este proyecto no utiliza Git para el despliegue. No ejecutes `git init` ni comandos de Git salvo que el área de TI indique lo contrario.
