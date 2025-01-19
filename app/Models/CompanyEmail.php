<?php

namespace App\Models;

use App\Models\Scopes\CompanyEmailsScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyEmail extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'api_json' => 'array',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new CompanyEmailsScope);
    }

    public function receipt_accounts()
    {
        return $this->hasMany(ReceiptAccount::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
