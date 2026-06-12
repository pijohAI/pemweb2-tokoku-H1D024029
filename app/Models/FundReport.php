<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['campaign_id', 'amount_spent', 'purpose', 'description', 'report_date'])]
class FundReport extends Model
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
            'report_date' => 'date',
            'amount_spent' => 'decimal:2',
        ];
    }

    /**
     * Get the campaign associated with this fund report.
     */
    public function campaign(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the photos/receipts for this fund report.
     */
    public function photos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FundReportPhoto::class);
    }
}
