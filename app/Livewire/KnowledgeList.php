<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\KnowledgeBase;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class KnowledgeList extends Component
{
    use WithPagination;

    public const TYPES = [
        'faq' => 'FAQ',
        'workaround' => 'Solución temporal',
        'known_error' => 'Error conocido',
    ];

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $category = '';

    #[Url]
    public string $type = '';

    public function mount(): void
    {
        abort_unless(auth()->check(), 403);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategory(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
    {
        $this->resetPage();
    }

    #[On('knowledge-saved')]
    public function refreshKnowledge(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();
        $canManage = $user && in_array($user->role, ['admin', 'tecnico'], true);

        $knowledge = KnowledgeBase::query()
            ->with(['category', 'creator'])
            ->when(! $canManage, function ($query) {
                $query->where('is_published', true);
            })
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%'.$this->search.'%');
            })
            ->when($this->category, function ($query) {
                $query->where('category_id', $this->category);
            })
            ->when($this->type, function ($query) {
                $query->where('type', $this->type);
            })
            ->latest()
            ->paginate(10);

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view('livewire.knowledge-list', [
            'knowledge' => $knowledge,
            'categories' => $categories,
            'types' => self::TYPES,
            'canManage' => $canManage,
        ]);
    }
}
