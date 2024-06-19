<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\Location;
use Illuminate\Support\Str;
use App\Models\Traits\LogsActivity;
use App\Models\PlannedApplicationType;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Http\Controllers\Admin\Traits\CurrencyFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlannedApplication extends Model
{
    use CrudTrait;
    use HasFactory;
    use LogsActivity;

    use CurrencyFormat;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
|--------------------------------------------------------------------------
    */

    protected $table = 'planned_applications';
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
    public function plannedApplicationType()
    {
        return $this->belongsTo(PlannedApplicationType::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

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
    public function getMbpsPriceAttribute()
    {
        $type = $this->plannedApplicationType->name;
        
        $type = explode(' / ', $type);

        if (is_array($type)) {
            $type = $type[0];
        }

        return $type .' :   '.$this->mbps . 'Mbps ----- ' . $this->currencyFormatAccessor($this->price);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
