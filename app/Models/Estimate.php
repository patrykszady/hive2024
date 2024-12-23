<?php

namespace App\Models;

use App\Models\Client;

use App\Models\Scopes\EstimateScope;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

use Carbon\Carbon;

class Estimate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['project_id', 'options', 'belongs_to_vendor_id', 'created_at', 'updated_at'];

    protected $casts = [
        'options' => 'array',
        // 'options.start_date' => 'date:Y-m-d',
        // 'options.end_date' => 'date:Y-m-d',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new EstimateScope);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function line_items()
    {
        return $this->belongsToMany(LineItem::class)->withPivot('id', 'name', 'category', 'sub_category', 'unit_type', 'cost', 'desc', 'notes', 'quantity', 'total', 'section_id')->withTimestamps();
    }

    public function estimate_line_items()
    {
        return $this->hasMany(EstimateLineItem::class);
    }

    public function estimate_sections()
    {
        return $this->hasMany(EstimateSection::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'belongs_to_vendor_id');
    }

    // public function getSectionsAttribute($value)
    // {
    //     // dd($value);
    //     //where not removed
    //     $sections = collect(json_decode($value, true));
    //     return $sections->where('deleted', '!=', true);
    //     // dd($sections->where('deleted', '!=', true));
    //     // foreach($sections as $section){
    //     //     if(isset($section['deleted'])){
    //     //         continue;
    //     //     }else{
    //     //         // $sections
    //     //     }
    //     // }
    //     // return json_decode($value, true);
    // }

    public function getClientAttribute()
    {
        return $this->project->clients()->wherePivot('vendor_id', $this->belongs_to_vendor_id)->first();
    }
    // public function getClient($vendor)
    // {
    //     dd($vendor);
    // }

    public function getStartDateAttribute()
    {
        if(isset($this->options['start_date'])){
            return Carbon::parse($this->options['start_date']);
        }else{
            return NULL;
        }
    }

    public function getEndDateAttribute()
    {
        if(isset($this->options['end_date'])){
            return Carbon::parse($this->options['end_date']);
        }else{
            return NULL;
        }
    }

    public function getReimbursmentsAttribute()
    {
        if(isset($this->options['include_reimbursement']) && $this->options['include_reimbursement'] == TRUE){
            return $this->project->finances['reimbursments'];
        }else{
            return NULL;
        }
    }

    public function getPaymentsAttribute()
    {
        if(isset($this->options['payments'])){
            return $this->options['payments'];
        }else{
            return NULL;
        }
    }

    public function getNumberAttribute()
    {
        $number =
            $this->belongs_to_vendor_id . '-' .
            $this->client->id . '-' .
            $this->project->id . '-' .
            $this->id;

        return $number;
    }
}
