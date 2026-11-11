<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Crea los triggers de auditoría sobre la tabla `tickets`.
     *
     * La variable de sesión MySQL @current_user_id es establecida por la
     * aplicación (middleware/observers) antes de cada operación; si no está
     * definida, el trigger registrará NULL en audit_logs.user_id.
     */
    public function up(): void
    {
        // Se eliminan por si existieran de una ejecución previa (idempotencia).
        DB::unprepared('DROP TRIGGER IF EXISTS trg_tickets_audit_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_tickets_audit_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_tickets_audit_delete');

        // Trigger 1: alta de ticket -> guarda el estado nuevo.
        DB::unprepared(<<<'SQL'
            CREATE TRIGGER trg_tickets_audit_insert
            AFTER INSERT ON tickets
            FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, table_name, record_id, new_values)
                VALUES (
                    @current_user_id,
                    'INSERT',
                    'tickets',
                    NEW.id,
                    JSON_OBJECT(
                        'ticket_number', NEW.ticket_number,
                        'status', NEW.status,
                        'priority', NEW.priority,
                        'technician_id', NEW.technician_id
                    )
                );
            END
        SQL);

        // Trigger 2: actualización -> guarda valores anteriores y nuevos.
        DB::unprepared(<<<'SQL'
            CREATE TRIGGER trg_tickets_audit_update
            AFTER UPDATE ON tickets
            FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, table_name, record_id, old_values, new_values)
                VALUES (
                    @current_user_id,
                    'UPDATE',
                    'tickets',
                    NEW.id,
                    JSON_OBJECT(
                        'status', OLD.status,
                        'priority', OLD.priority,
                        'technician_id', OLD.technician_id
                    ),
                    JSON_OBJECT(
                        'status', NEW.status,
                        'priority', NEW.priority,
                        'technician_id', NEW.technician_id
                    )
                );
            END
        SQL);

        // Trigger 3: eliminación -> guarda el estado anterior.
        DB::unprepared(<<<'SQL'
            CREATE TRIGGER trg_tickets_audit_delete
            AFTER DELETE ON tickets
            FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, table_name, record_id, old_values)
                VALUES (
                    @current_user_id,
                    'DELETE',
                    'tickets',
                    OLD.id,
                    JSON_OBJECT(
                        'ticket_number', OLD.ticket_number,
                        'status', OLD.status
                    )
                );
            END
        SQL);
    }

    /**
     * Elimina los triggers al revertir la migración.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_tickets_audit_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_tickets_audit_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_tickets_audit_delete');
    }
};
