<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_users'       => User::where('role', 'user')->count(),
            'total_campaigns'   => Campaign::count(),
            'active_campaigns'  => Campaign::where('status', 'active')->count(),
            'total_categories'  => Category::count(),
            'total_donations'   => Donation::where('status', 'success')->count(),
            'total_funds'       => Donation::where('status', 'success')->sum('amount'),
            'pending_donations' => Donation::where('status', 'pending')->count(),
        ];

        $recentDonations = Donation::with(['campaign', 'user'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $topCampaigns = Campaign::withSum(['donations as collected' => fn ($q) => $q->where('status', 'success')], 'amount')
            ->orderByDesc('collected')
            ->limit(5)
            ->get();

        return view('livewire.admin.dashboard', compact('stats', 'recentDonations', 'topCampaigns'))
            ->layout('layouts.admin');
    }
}
