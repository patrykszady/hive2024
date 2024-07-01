<?php

namespace App\Models;

use App\Observers\TaskObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

#[ObservedBy([TaskObserver::class])]
class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'project_id', 'start_date', 'end_date', 'duration', 'order', 'type', 'vendor_id', 'user_id', 'progress', 'notes', 'belongs_to_vendor_id', 'created_by_user_id', 'created_at', 'updated_at', 'deleted_at'];

    // protected $hidden = ['date', 'direction'];
    // protected $appends = ['date'];
    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function userId(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => empty($value) ? NULL : $value,
        );
    }

    protected function vendorId(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => empty($value) ? NULL : $value,
        );
    }

    //5/7/2024 should just work because of $casts above
    protected function startDate(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value != NULL ? Carbon::parse($value)->format('Y-m-d') : NULL,
        );
    }

    //5/7/2024 should just work because of $casts above
    protected function endDate(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value != NULL ? Carbon::parse($value)->format('Y-m-d') : NULL,
        );
    }
}
