<?php

namespace App\Models;

use App\Models\Scopes\VendorScope;
use App\Models\Scopes\ClientScope;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Laravel\Scout\Searchable;

class Vendor extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['business_name', 'business_type', 'sheets_type', 'category_id', 'address', 'address_2', 'city', 'state', 'zip_code', 'business_phone', 'business_email', 'created_at', 'updated_at'];

    protected $appends = ['name'];

    protected static function booted()
    {
        static::addGlobalScope(new VendorScope);
    }

    //Searchable / Typesense
    public function toSearchableArray(): array
    {
        return array_merge($this->toArray(),[
            'id' => (string) $this->id,
            'business_name' => $this->business_name,
            'business_type' => $this->business_type,
            'created_at' => $this->created_at->timestamp,
        ]);
    }

    /**
     * Get the name of the index associated with the model.
     */
    public function searchableAs(): string
    {
        return 'vendors_index';
    }

    public function vendor_categories()
    {
        return $this->belongsToMany(VendorCategory::class, 'category_vendor', 'vendor_id', 'vendor_category_id')->withTimestamps();
    }

    //Vendors that belong to Logged in vendor / via $user->primary_vendor_id
    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'vendors_vendor', 'belongs_to_vendor_id')->withoutGlobalScopes()->withTimestamps();
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }

    public function estimates()
    {
        return $this->belongsToMany(Estimate::class)->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function vendor()
    {
        return $this->belongsToMany(Vendor::class, 'vendors_vendor', 'vendor_id')->withTimestamps();
    }

    public function receipt_account()
    {
        return $this->hasOne(ReceiptAccount::class);
    }

    public function receipt_accounts()
    {
        return $this->hasMany(ReceiptAccount::class, 'belongs_to_vendor_id');
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function task()
    {
        return $this->hasMany(Task::class);
    }

    public function transactions_bulk_match()
    {
        return $this->hasMany(TransactionBulkMatch::class);
    }

    public function company_emails()
    {
        return $this->hasMany(CompanyEmail::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function vendor_docs()
    {
        return $this->hasMany(VendorDoc::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function banks()
    {
        return $this->hasMany(Bank::class);
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }

    public function bank_accounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    // public function project_status()
    // {
    //     return $this->hasMany(ProjectStatus::class, '');
    // }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function hours()
    {
        return $this->hasMany(Hour::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->with('vendor')->withPivot(['is_employed', 'role_id', 'via_vendor_id', 'start_date', 'end_date', 'hourly_rate']);
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class);
    }

    public function client()
    {
        return $this->hasOne(Client::class)->withoutGlobalScope(ClientScope::class);
    }

    public function getRegistrationAttribute($value)
    {
        $value = json_decode($value, true);
        $status_array = ['registered', 'vendor_info', 'team_members', 'user_registered', 'banks_registered', 'emails_registered'];

        foreach($status_array as $status){
            if(!isset($value[$status])){
                $value[$status] = false;
            }
        }

        return $value;
    }

    public function getFullAddressAttribute()
    {
        if($this->address_2){
            $address = $this->address . '<br>' . $this->address_2 . '<br>' . $this->city . ', ' . $this->state . ' ' . $this->zip_code;
        }elseif($this->address){
            $address = $this->address . '<br>' . $this->city . ', ' . $this->state . ' ' . $this->zip_code;
        }else{
            $address = NULL;
        }

        return $address;
    }

    public function getBusienssNameAttribute()
    {
        if(is_null($this->business_name)){
            return 'NO VENDOR';
        }else{
            return $this->business_name;
        }
    }

    public function getNameAttribute()
    {
        if(is_null($this->business_name)){
            return 'NO VENDOR';
        }elseif($this->biz_type == 4 AND !is_null($this->users()->first())){
            $name = $this->users()->first()->first_name . ' ' . $this->users()->first()->last_name;
            return $name;
        }else{
            //delete. INC, DBA..and if it's too long
            $name = explode(",",$this->business_name);
            return $name[0];
        }
    }

    public function getAddressMapURI()
    {
        $url = 'https://maps.apple.com/?q=' . $this->address . ', ' . $this->city . ', ' . $this->state . ', ' . $this->zip_code;
        return $url;
    }


    public function scopeHiveVendors($query)
    {
        return $query->withoutGlobalScopes()->where('business_type', 'Sub')->where('registration->registered', true);
    }

    // public function setBusinessName($value)
    // {
    //     // dd($value);
    //     $this->attributes['business_name'] = ucwords($value);
    // }

    // public function businessName(): Attribute
    // {
    //     return Attribute::make(
    //         set: fn ($value) => ucwords($value),
    //     );
    // }
}
