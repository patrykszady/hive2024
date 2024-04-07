<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'project_id', 'start_date', 'position', 'type', 'vendor_id', 'user_id', 'progress', 'notes', 'belongs_to_vendor_id', 'created_by_user_id', 'created_at', 'updated_at', 'deleted_at'];

    // protected $casts = [
    //     'start_date' => 'date:Y-m-d',
    //     'deleted_at' => 'date:Y-m-d',
    // ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
