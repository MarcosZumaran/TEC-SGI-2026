<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Department;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SettingsCategories extends Component
{
    /**
     * @var array<int, array{id: int, name: string, description: string|null, categories_count: int}>
     */
    public array $departments = [];

    /**
     * @var array<int, array{id: int, name: string, department_id: int|null, department_name: string|null}>
     */
    public array $categories = [];

    public bool $showDepartmentForm = false;

    public ?int $editingDepartmentId = null;

    public string $departmentName = '';

    public string $departmentDescription = '';

    public function mount(): void
    {
        $this->authorizeSettings();
        $this->loadData();
    }

    public function createDepartment(): void
    {
        $this->authorizeSettings();
        $this->resetDepartmentForm();
        $this->showDepartmentForm = true;
    }

    public function editDepartment(int $id): void
    {
        $this->authorizeSettings();

        $department = Department::findOrFail($id);

        $this->editingDepartmentId = $department->id;
        $this->departmentName = $department->name;
        $this->departmentDescription = $department->description ?? '';
        $this->showDepartmentForm = true;
    }

    public function saveDepartment(): void
    {
        $this->authorizeSettings();

        $this->departmentName = trim($this->departmentName);

        $validated = $this->validate([
            'departmentName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments', 'name')->ignore($this->editingDepartmentId),
            ],
            'departmentDescription' => ['nullable', 'string', 'max:255'],
        ]);

        if ($this->editingDepartmentId !== null) {
            Department::findOrFail($this->editingDepartmentId)->update([
                'name' => $validated['departmentName'],
                'description' => $validated['departmentDescription'] ?: null,
            ]);
        } else {
            Department::create([
                'name' => $validated['departmentName'],
                'description' => $validated['departmentDescription'] ?: null,
            ]);
        }

        $this->resetDepartmentForm();
        $this->loadData();

        session()->flash('categories_message', 'Departamento guardado correctamente.');
    }

    public function deleteDepartment(int $id): void
    {
        $this->authorizeSettings();

        Department::findOrFail($id)->delete();

        if ($this->editingDepartmentId === $id) {
            $this->resetDepartmentForm();
        }

        $this->loadData();

        session()->flash('categories_message', 'Departamento eliminado correctamente.');
    }

    public function cancelDepartmentForm(): void
    {
        $this->resetDepartmentForm();
    }

    public function updateCategoryDepartment(int $categoryId, mixed $departmentId): void
    {
        $this->authorizeSettings();

        $departmentId = $departmentId === '' ? null : $departmentId;

        $validated = validator(
            ['department_id' => $departmentId],
            ['department_id' => ['nullable', 'integer', 'exists:departments,id']]
        )->validate();

        Category::findOrFail($categoryId)->update([
            'department_id' => $validated['department_id'] ?? null,
        ]);

        $this->loadData();

        session()->flash('categories_message', 'Departamento de la categoría actualizado.');
    }

    private function loadData(): void
    {
        $this->departments = Department::query()
            ->withCount('categories')
            ->orderBy('name')
            ->get()
            ->map(fn (Department $department): array => [
                'id' => $department->id,
                'name' => $department->name,
                'description' => $department->description,
                'categories_count' => $department->categories_count,
            ])
            ->all();

        $this->categories = Category::query()
            ->with('department')
            ->orderBy('name')
            ->get()
            ->map(fn (Category $category): array => [
                'id' => $category->id,
                'name' => $category->name,
                'department_id' => $category->department_id,
                'department_name' => $category->department?->name,
            ])
            ->all();
    }

    private function resetDepartmentForm(): void
    {
        $this->editingDepartmentId = null;
        $this->departmentName = '';
        $this->departmentDescription = '';
        $this->showDepartmentForm = false;
        $this->resetValidation();
    }

    private function authorizeSettings(): void
    {
        abort_unless(
            auth()->check() && auth()->user()->can('settings.manage'),
            403
        );
    }

    public function render()
    {
        return view('livewire.settings-categories');
    }
}
