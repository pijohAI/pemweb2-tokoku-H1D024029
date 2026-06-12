<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CampaignManager extends Component
{
    use WithPagination, WithFileUploads;

    // Form fields
    public string $title = '';
    public string $description = '';
    public ?int $categoryId = null;
    public string $targetAmount = '';
    public string $endDate = '';
    public string $status = 'active';
    public $imageFile = null;
    public ?string $existingImage = null;
    public ?int $editingId = null;

    // UI state
    public bool $showModal = false;
    public string $search = '';
    public string $filterStatus = '';

    protected $queryString = [
        'search'       => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    protected function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['required', 'string'],
            'categoryId'   => ['required', 'exists:categories,id'],
            'targetAmount' => ['required', 'numeric', 'min:10000'],
            'endDate'      => ['required', 'date', 'after:today'],
            'status'       => ['required', 'in:active,completed,cancelled'],
            'imageFile'    => [$this->editingId ? 'nullable' : 'required', 'image', 'max:2048'],
        ];
    }

    protected $messages = [
        'title.required'        => 'Judul kampanye wajib diisi.',
        'description.required'  => 'Deskripsi wajib diisi.',
        'categoryId.required'   => 'Kategori wajib dipilih.',
        'targetAmount.required' => 'Target dana wajib diisi.',
        'targetAmount.min'      => 'Target dana minimal Rp 10.000.',
        'endDate.required'      => 'Tanggal selesai wajib diisi.',
        'endDate.after'         => 'Tanggal selesai harus setelah hari ini.',
        'imageFile.required'    => 'Gambar kampanye wajib diunggah.',
        'imageFile.max'         => 'Ukuran gambar maksimal 2MB.',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
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
        $campaign = Campaign::findOrFail($id);
        $this->editingId     = $campaign->id;
        $this->title         = $campaign->title;
        $this->description   = $campaign->description;
        $this->categoryId    = $campaign->category_id;
        $this->targetAmount  = $campaign->target_amount;
        $this->endDate       = $campaign->end_date?->format('Y-m-d') ?? '';
        $this->status        = $campaign->status;
        $this->existingImage = $campaign->image_path;
        $this->imageFile     = null;
        $this->showModal     = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'user_id'       => auth()->id(),
            'category_id'   => $this->categoryId,
            'title'         => $this->title,
            'slug'          => Str::slug($this->title),
            'description'   => $this->description,
            'target_amount' => $this->targetAmount,
            'end_date'      => $this->endDate,
            'status'        => $this->status,
        ];

        if ($this->imageFile) {
            $path = $this->imageFile->store('campaigns', 'public');
            $data['image_path'] = $path;
        }

        if ($this->editingId) {
            Campaign::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Kampanye berhasil diperbarui.');
        } else {
            $data['current_amount'] = 0;
            Campaign::create($data);
            session()->flash('success', 'Kampanye berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function updateStatus(int $id, string $status): void
    {
        Campaign::findOrFail($id)->update(['status' => $status]);
        session()->flash('success', 'Status kampanye berhasil diubah.');
    }

    public function delete(int $id): void
    {
        $campaign = Campaign::findOrFail($id);

        if ($campaign->donations()->where('status', 'success')->count() > 0) {
            session()->flash('error', 'Kampanye tidak bisa dihapus karena sudah memiliki donasi sukses.');
            return;
        }

        $campaign->delete();
        session()->flash('success', 'Kampanye berhasil dihapus.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->title         = '';
        $this->description   = '';
        $this->categoryId    = null;
        $this->targetAmount  = '';
        $this->endDate       = '';
        $this->status        = 'active';
        $this->imageFile     = null;
        $this->existingImage = null;
        $this->editingId     = null;
        $this->resetValidation();
    }

    public function render()
    {
        $campaigns = Campaign::query()
            ->with(['category', 'user'])
            ->when($this->search, fn ($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->withCount(['donations as success_donations_count' => fn ($q) => $q->where('status', 'success')])
            ->orderByDesc('created_at')
            ->paginate(10);

        $categories = Category::orderBy('name')->get();

        return view('livewire.admin.campaign-manager', compact('campaigns', 'categories'))
            ->layout('layouts.admin');
    }
}
