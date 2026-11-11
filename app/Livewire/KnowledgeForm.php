<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\KnowledgeBase;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class KnowledgeForm extends Component
{
    public ?int $knowledgeId = null;

    public string $category_id = '';

    public string $type = 'faq';

    public string $title = '';

    public string $content = '';

    public bool $is_published = false;

    public const TYPES = [
        'faq' => 'FAQ',
        'workaround' => 'Solución temporal',
        'known_error' => 'Error conocido',
    ];

    public function mount(?int $knowledgeId = null): void
    {
        $this->authorizeEditor();

        if ($knowledgeId === null) {
            return;
        }

        $article = KnowledgeBase::findOrFail($knowledgeId);

        $this->knowledgeId = $article->id;
        $this->category_id = (string) $article->category_id;
        $this->type = $article->type;
        $this->title = $article->title;
        $this->content = $article->content;
        $this->is_published = $article->is_published;
    }

    protected function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'type' => ['required', Rule::in(array_keys(self::TYPES))],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'is_published' => ['required', 'boolean'],
        ];
    }

    public function save(): void
    {
        $this->authorizeEditor();

        $this->title = trim($this->title);

        $validated = $this->validate();

        $isEditing = $this->knowledgeId !== null;

        DB::transaction(function () use ($validated, $isEditing) {
            $data = [
                'category_id' => $validated['category_id'],
                'type' => $validated['type'],
                'title' => $validated['title'],
                'content' => $validated['content'],
                'is_published' => $validated['is_published'],
            ];

            if ($isEditing) {
                $article = KnowledgeBase::findOrFail($this->knowledgeId);
                $article->update($data);

                return;
            }

            $data['created_by'] = auth()->id();

            KnowledgeBase::create($data);
        });

        if (! $isEditing) {
            $this->resetForm();
        }

        session()->flash(
            'message',
            $isEditing ? 'Artículo actualizado exitosamente.' : 'Artículo creado exitosamente.'
        );

        $this->dispatch('knowledge-saved');
    }

    protected function authorizeEditor(): void
    {
        $user = auth()->user();

        abort_unless($user && in_array($user->role, ['admin', 'tecnico'], true), 403);
    }

    protected function resetForm(): void
    {
        $this->reset([
            'category_id',
            'title',
            'content',
        ]);

        $this->type = 'faq';
        $this->is_published = false;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.knowledge-form', [
            'categories' => Category::query()->orderBy('name')->get(),
            'types' => self::TYPES,
        ]);
    }
}
