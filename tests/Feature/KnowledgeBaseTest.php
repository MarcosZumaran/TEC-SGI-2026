<?php

use App\Livewire\KnowledgeForm;
use App\Models\Category;
use App\Models\KnowledgeBase;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('un usuario normal solo ve artículos publicados', function () {
    $user = User::factory()->create(['role' => 'usuario']);
    $category = Category::factory()->create();

    $published = KnowledgeBase::create([
        'category_id' => $category->id,
        'type' => 'faq',
        'title' => 'Artículo publicado de prueba',
        'content' => 'Contenido visible para todos los usuarios.',
        'is_published' => true,
        'created_by' => $user->id,
    ]);

    $draft = KnowledgeBase::create([
        'category_id' => $category->id,
        'type' => 'known_error',
        'title' => 'Borrador privado de prueba',
        'content' => 'Contenido que no debe mostrarse a usuarios normales.',
        'is_published' => false,
        'created_by' => $user->id,
    ]);

    actingAs($user);

    $this->get(route('knowledge.index'))
        ->assertOk()
        ->assertSee($published->title)
        ->assertDontSee($draft->title);
});

it('un técnico puede ver borradores y artículos publicados', function () {
    $technician = User::factory()->create(['role' => 'tecnico']);
    $category = Category::factory()->create();

    $published = KnowledgeBase::create([
        'category_id' => $category->id,
        'type' => 'workaround',
        'title' => 'Solución publicada',
        'content' => 'Solución visible para todos.',
        'is_published' => true,
        'created_by' => $technician->id,
    ]);

    $draft = KnowledgeBase::create([
        'category_id' => $category->id,
        'type' => 'known_error',
        'title' => 'Borrador técnico',
        'content' => 'Borrador visible solo para gestión TI.',
        'is_published' => false,
        'created_by' => $technician->id,
    ]);

    actingAs($technician);

    $this->get(route('knowledge.index'))
        ->assertOk()
        ->assertSee($published->title)
        ->assertSee($draft->title);
});

it('un técnico puede crear un artículo con el created_by correcto', function () {
    $technician = User::factory()->create(['role' => 'tecnico']);
    $category = Category::factory()->create();

    actingAs($technician);

    Livewire::test(KnowledgeForm::class)
        ->set('category_id', (string) $category->id)
        ->set('type', 'known_error')
        ->set('title', 'Error conocido de red')
        ->set('content', 'Descripción del error y su solución temporal.')
        ->set('is_published', true)
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('knowledge-saved');

    $this->assertDatabaseHas('knowledge_base', [
        'category_id' => $category->id,
        'type' => 'known_error',
        'title' => 'Error conocido de red',
        'is_published' => true,
        'created_by' => $technician->id,
    ]);
});

it('un usuario normal no puede crear un artículo', function () {
    $user = User::factory()->create(['role' => 'usuario']);

    actingAs($user);

    Livewire::test(KnowledgeForm::class)->assertForbidden();
});
