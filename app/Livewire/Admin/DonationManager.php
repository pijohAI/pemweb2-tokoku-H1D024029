<?php

namespace App\Livewire\Admin;

use App\Models\Donation;
use Livewire\Component;
use Livewire\WithPagination;

class DonationManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';

    protected $queryString = [
        'search'       => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function verify(int $id): void
    {
        Donation::findOrFail($id)->update(['status' => 'success']);
        session()->flash('success', 'Donasi berhasil diverifikasi.');
    }

    public function reject(int $id): void
    {
        Donation::findOrFail($id)->update(['status' => 'failed']);
        session()->flash('success', 'Donasi telah ditolak.');
    }

    public function render()
    {
        $donations = Donation::query()
            ->with(['campaign', 'user'])
            ->when($this->search, fn ($q) => $q->where(function ($q2) {
                $q2->where('donor_name', 'like', '%' . $this->search . '%')
                   ->orWhereHas('campaign', fn ($cq) => $cq->where('title', 'like', '%' . $this->search . '%'));
            }))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.admin.donation-manager', compact('donations'))
            ->layout('layouts.admin');
    }
}
