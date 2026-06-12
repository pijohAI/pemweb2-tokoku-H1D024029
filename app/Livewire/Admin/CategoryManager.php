<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryManager extends Component
{
    use WithPagination;

    // Form fields
    public string $name = '';
    public string $description = '';
    public ?int $editingId = null;

    // UI state
    public bool $showModal = false;
    public string $search = '';

    protected $queryString = ['search' => ['except' => '']];

    protected function rules(): array
    {
        $uniqueRule = 'unique:categories,name';
        if ($this->editingId) {
            $uniqueRule .= ',' . $this->editingId;
        }

        return [
            'name'        => ['required', 'string', 'max:100', $uniqueRule],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }

    protected $messages = [
        'name.required' => 'Nama kategori wajib diisi.',
        'name.unique'   => 'Nama kategori sudah ada.',
        'name.max'      => 'Nama kategori maksimal 100 karakter.',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $category = Category::findOrFail($id);
        $this->editingId   = $category->id;
        $this->name        = $category->name;
        $this->description = $category->description ?? '';
        $this->showModal   = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'        => $this->name,
            'slug'        => Str::slug($this->name),
            'description' => $this->description,
        ];

        if ($this->editingId) {
            Category::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Kategori berhasil diperbarui.');
        } else {
            Category::create($data);
            session()->flash('success', 'Kategori berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function delete(int $id): void
    {
        $category = Category::findOrFail($id);

        if ($category->campaigns()->count() > 0) {
            session()->flash('error', 'Kategori tidak bisa dihapus karena masih memiliki kampanye.');
            return;
        }

        $category->delete();
        session()->flash('success', 'Kategori berhasil dihapus.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->name        = '';
        $this->description = '';
        $this->editingId   = null;
        $this->resetValidation();
    }

    public function render()
    {
        $categories = Category::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->withCount('campaigns')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.category-manager', compact('categories'))
            ->layout('layouts.admin');
    }
}
