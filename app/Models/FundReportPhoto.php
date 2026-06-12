<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['fund_report_id', 'photo_path', 'caption'])]
class FundReportPhoto extends Model
{
    use HasFactory;

    /**
     * Get the fund report that owns this photo.
     */
    public function report(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FundReport::class, 'fund_report_id');
    }
}
