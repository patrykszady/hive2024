<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Traits\Sortable;

class EstimateLineItem extends Pivot
// class EstimateLineItem extends Model
{
    use HasFactory, SoftDeletes, Sortable;
    //via_vendor
    // public function via_vendor()
    // {
    //     return $this->belongsTo(Vendor::class, 'via_vendor_id')->withoutGlobalScopes();
    // }
    protected function scopeSortable($query, $estimate_line_item)
    {
        return $estimate_line_item->section->estimate_line_items();
    }

    public function estimate()
    {
        return $this->belongsTo(Estimate::class)->withTimestamps();
    }

    public function section()
    {
        return $this->belongsTo(EstimateSection::class);
    }
}
