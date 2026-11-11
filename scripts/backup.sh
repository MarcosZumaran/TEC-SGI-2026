#!/usr/bin/env bash
#
# Respaldo de TEC-SGI 2026
# ------------------------
# Exporta la base de datos MariaDB/MySQL a un archivo .sql y lo comprime
# en un .zip junto con los archivos públicos de storage/app/public.
#
# Las credenciales se leen de las variables de entorno o del archivo .env
# del proyecto. Se pueden sobrescribir así:
#
#   DB_HOST=127.0.0.1 DB_PORT=3306 DB_DATABASE=tec_sgi_db \
#   DB_USERNAME=usuario DB_PASSWORD=clave ./scripts/backup.sh
#
# Programación recomendada en el crontab del servidor del instituto
# (todos los días a las 11:00 PM):
#
#   chmod +x /ruta/al/proyecto/scripts/backup.sh
#   crontab -e
#
#   # Respaldo diario de TEC-SGI 2026 a las 23:00
#   0 23 * * * cd /ruta/al/proyecto && ./scripts/backup.sh >> storage/logs/backup.log 2>&1
#
# Los respaldos se guardan en la carpeta backups/ del proyecto. Esa carpeta
# debe tener permisos restringidos y, preferiblemente, copiarse también a un
# almacenamiento externo o servidor de respaldo.
#
set -Eeuo pipefail

PROJECT_ROOT="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.." && pwd)"
BACKUP_DIR="${BACKUP_DIR:-${PROJECT_ROOT}/backups}"
STORAGE_DIR="${PROJECT_ROOT}/storage/app/public"
ENV_FILE="${ENV_FILE:-${PROJECT_ROOT}/.env}"
DATE_STAMP="$(date +%Y-%m-%d_%H-%M-%S)"

umask 077

read_env_value() {
    local key="$1"
    local line=""

    if [[ -f "$ENV_FILE" ]]; then
        line="$(grep -E "^[[:space:]]*${key}[[:space:]]*=" "$ENV_FILE" | tail -n 1 || true)"
        line="${line#*=}"
        line="${line%$'\r'}"
        line="${line%\"}"
        line="${line#\"}"
        line="${line%\'}"
        line="${line#\'}"
    fi

    printf '%s' "$line"
}

DB_HOST="${DB_HOST:-$(read_env_value DB_HOST)}"
DB_PORT="${DB_PORT:-$(read_env_value DB_PORT)}"
DB_DATABASE="${DB_DATABASE:-$(read_env_value DB_DATABASE)}"
DB_USERNAME="${DB_USERNAME:-$(read_env_value DB_USERNAME)}"
DB_PASSWORD="${DB_PASSWORD:-$(read_env_value DB_PASSWORD)}"

: "${DB_HOST:=127.0.0.1}"
: "${DB_PORT:=3306}"
: "${DB_DATABASE:=tec_sgi_db}"
: "${DB_USERNAME:=root}"
DB_PASSWORD="${DB_PASSWORD:-}"

MYSQLDUMP_BIN="${MYSQLDUMP_BIN:-mysqldump}"

if ! command -v "$MYSQLDUMP_BIN" >/dev/null 2>&1; then
    echo "Error: no se encontró $MYSQLDUMP_BIN. Instala mariadb-client o mysql-client." >&2
    exit 1
fi

if ! command -v zip >/dev/null 2>&1; then
    echo "Error: no se encontró el comando zip. Instala el paquete zip." >&2
    exit 1
fi

mkdir -p "$BACKUP_DIR"

WORK_DIR="$(mktemp -d "${BACKUP_DIR}/.backup-tmp.XXXXXX")"
trap 'rm -rf "$WORK_DIR"' EXIT

SQL_FILE="${WORK_DIR}/${DB_DATABASE}_${DATE_STAMP}.sql"
ZIP_FILE="${BACKUP_DIR}/${DB_DATABASE}_${DATE_STAMP}.zip"

MYSQLDUMP_ARGS=(
    "--host=${DB_HOST}"
    "--port=${DB_PORT}"
    "--user=${DB_USERNAME}"
    "--single-transaction"
    "--quick"
    "--routines"
    "--triggers"
    "--events"
    "--default-character-set=utf8mb4"
    "${DB_DATABASE}"
)

echo "Exportando la base de datos ${DB_DATABASE}..."

if [[ -n "$DB_PASSWORD" ]]; then
    MYSQL_PWD="$DB_PASSWORD" "$MYSQLDUMP_BIN" "${MYSQLDUMP_ARGS[@]}" > "$SQL_FILE"
else
    "$MYSQLDUMP_BIN" "${MYSQLDUMP_ARGS[@]}" > "$SQL_FILE"
fi

if [[ ! -s "$SQL_FILE" ]]; then
    echo "Error: el archivo SQL quedó vacío. No se creará el respaldo." >&2
    exit 1
fi

echo "Comprimiendo base de datos..."

(
    cd "$WORK_DIR"
    zip -q -r "$ZIP_FILE" "$(basename "$SQL_FILE")"
)

if [[ -d "$STORAGE_DIR" ]]; then
    echo "Agregando storage/app/public al respaldo..."

    (
        cd "$PROJECT_ROOT"
        zip -q -r "$ZIP_FILE" "storage/app/public"
    )
else
    echo "Advertencia: no existe ${STORAGE_DIR}. Solo se respaldó la base de datos." >&2
fi

echo "Respaldo creado correctamente: ${ZIP_FILE}"
