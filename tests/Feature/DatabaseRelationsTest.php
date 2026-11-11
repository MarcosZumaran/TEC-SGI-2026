<?php

use App\Models\Asset;
use App\Models\AssetSpec;
use App\Models\Category;
use App\Models\Location;
use App\Models\Site;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('registra un ticket con jerarquía de activos, especificaciones y soft delete', function () {
    // -----------------------------------------------------------------
    // 1. SETUP: ubicación, categoría y técnico
    // -----------------------------------------------------------------
    $site = Site::create([
        'name' => 'Sede Test Relations',
        'address' => 'Dirección de prueba',
    ]);

    $location = Location::create([
        'site_id' => $site->id,
        'code' => 'LAB-TEST',
        'name' => 'Laboratorio Test',
    ]);

    $category = Category::create([
        'name' => 'Hardware Test',
    ]);

    $technician = User::create([
        'name' => 'Técnico Test',
        'email' => 'tecnico.test@tec.edu.pe',
        'password' => 'secret123',
        'role' => 'tecnico',
    ]);

    // -----------------------------------------------------------------
    // 2. INVENTARIO: PC + especificaciones + monitor hijo
    // -----------------------------------------------------------------
    $pc = Asset::create([
        'asset_code' => 'PC-TEST-001',
        'type' => 'PC',
        'brand' => 'Dell',
        'model' => 'OptiPlex 7090',
        'location_id' => $location->id,
        'responsible_user_id' => $technician->id,
        'status' => 'Operativo',
    ]);

    AssetSpec::create([
        'asset_id' => $pc->id,
        'processor' => 'Intel Core i5',
        'ram' => '16 GB',
        'disk' => '512 GB SSD',
        'os' => 'Windows 11',
    ]);

    $monitor = Asset::create([
        'asset_code' => 'MON-TEST-001',
        'type' => 'MON',
        'parent_id' => $pc->id,
        'location_id' => $location->id,
    ]);

    // -----------------------------------------------------------------
    // 3. INCIDENCIA: ticket asociado al equipo y al técnico
    // -----------------------------------------------------------------
    $ticket = Ticket::create([
        'ticket_number' => 'TKT-2026-0001',
        'reporter_id' => $technician->id,
        'technician_id' => $technician->id,
        'asset_id' => $pc->id,
        'location_id' => $location->id,
        'category_id' => $category->id,
        'description' => 'La PC no enciende',
        'priority' => 'Alta',
    ]);

    $ticket->refresh();

    // -----------------------------------------------------------------
    // 4. ASERCIONES
    // -----------------------------------------------------------------
    expect($ticket->exists)->toBeTrue()
        ->and($ticket->status)->toBe('Abierto')
        ->and($pc->children()->count())->toBe(1)
        ->and($pc->children->first()->asset_code)->toBe('MON-TEST-001')
        ->and($monitor->parent->asset_code)->toBe('PC-TEST-001')
        ->and($ticket->technician->name)->toBe('Técnico Test')
        ->and($pc->specs->ram)->toBe('16 GB');

    // -----------------------------------------------------------------
    // 5. SOFT DELETE: el ticket deja de ser visible pero sigue en la BD
    // -----------------------------------------------------------------
    $ticket->delete();

    expect($ticket->trashed())->toBeTrue()
        ->and($ticket->deleted_at)->not->toBeNull()
        ->and(Ticket::find($ticket->id))->toBeNull()
        ->and(Ticket::withTrashed()->find($ticket->id))->not->toBeNull()
        ->and(Ticket::withTrashed()->find($ticket->id)->deleted_at)->not->toBeNull();
});
