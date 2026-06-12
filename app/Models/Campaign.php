<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'category_id',
    'title',
    'slug',
    'description',
    'target_amount',
    'current_amount',
    'end_date',
    'status',
    'image_path'
])]
class Campaign extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'end_date' => 'datetime',
            'target_amount' => 'decimal:2',
            'current_amount' => 'decimal:2',
        ];
    }

    /**
     * Get the user who created the campaign.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category of the campaign.
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the donations for the campaign.
     */
    public function donations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Get the updates posted for the campaign.
     */
    public function updates(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CampaignUpdate::class);
    }

    /**
     * Get the fund reports for the campaign.
     */
    public function reports(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FundReport::class);
    }
}
