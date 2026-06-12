<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\CategoryManager;
use App\Livewire\Admin\CampaignManager;
use App\Livewire\Admin\DonationManager;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboard::class)->name('dashboard');
    Route::get('/categories', CategoryManager::class)->name('categories');
    Route::get('/campaigns', CampaignManager::class)->name('campaigns');
    Route::get('/donations', DonationManager::class)->name('donations');
});

require __DIR__.'/settings.php';
