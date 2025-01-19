<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['date', 'origin', 'notes', 'user_id', 'lead_data', 'belongs_to_vendor_id', 'created_by_user_id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'date' => 'date:Y-m-d H:i:s',
        'deleted_at' => 'date:Y-m-d',
        'lead_data' => AsArrayObject::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statuses()
    {
        return $this->hasMany(LeadStatus::class);
    }

    public function last_status(){
        return $this->hasOne(LeadStatus::class)->orderBy('created_at', 'DESC')->latest();
    }
}
