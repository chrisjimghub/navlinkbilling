<?php

namespace App\Models;

use App\Models\Otc;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Subscription;
use App\Models\ContractPeriod;
use App\Models\PlannedApplication;
use App\Models\Traits\LogsActivity;
use App\Models\PlannedApplicationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use CrudTrait;
    use HasFactory;
    use LogsActivity;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'customers';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function subscription()
    // {
    //     return $this->belongsTo(Subscription::class);
    // }

    // public function plannedApplicationType()
    // {
    //     return $this->belongsTo(PlannedApplicationType::class);
    // }

    // public function plannedApplication()
    // {
    //     return $this->belongsTo(PlannedApplication::class);
    // }

    // public function otcs()
    // {
    //     return $this->belongsToMany(Otc::class, 'customer_otc', 'customer_id', 'otc_id');
    // }

    // public function contractPeriods()
    // {
    //     return $this->belongsToMany(ContractPeriod::class, 'contract_period_customer', 'customer_id', 'contract_period_id')->withTimestamps();
    // }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setSignatureAttribute($value)
    {
        if (Str::startsWith($value, 'data:image/png;base64,')) {
            $base64Image = substr($value, strpos($value, ',') + 1);
            $image = base64_decode($base64Image);

            $imageName = 'signature_' . time() . '.png'; // Generate a unique image name
            $path = 'signature/' . $imageName;

            Storage::disk('public')->put($path, $image);

            $this->attributes['signature'] = $path;
        } else {
            $this->attributes['signature'] = $value;
        }
    }

    


}
