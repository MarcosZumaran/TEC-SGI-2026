<?php

use App\Livewire\SettingsCategories;
use App\Models\Category;
use App\Models\Department;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('permite al administrador crear un departamento', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    actingAs($admin);

    Livewire::test(SettingsCategories::class)
        ->set('departmentName', 'Soporte Técnico')
        ->set('departmentDescription', 'Atención a usuarios')
        ->call('saveDepartment')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('departments', [
        'name' => 'Soporte Técnico',
        'description' => 'Atención a usuarios',
    ]);
});

it('permite al administrador asignar un departamento a una categoría', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $department = Department::create([
        'name' => 'Infraestructura',
        'description' => null,
    ]);
    $category = Category::create(['name' => 'Redes']);

    actingAs($admin);

    Livewire::test(SettingsCategories::class)
        ->call('updateCategoryDepartment', $category->id, $department->id)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'department_id' => $department->id,
    ]);
});

it('rechaza a un usuario normal en la gestión de categorías', function () {
    $user = User::factory()->create(['role' => 'usuario']);
    actingAs($user);

    Livewire::test(SettingsCategories::class)->assertForbidden();
});
