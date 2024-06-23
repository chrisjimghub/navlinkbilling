<?php

namespace App\Models;

use App\Models\Model;
use App\Models\Account;
use App\Models\Location;
use App\Models\PlannedApplicationType;
use App\Http\Controllers\Admin\Traits\CurrencyFormat;

class PlannedApplication extends Model
{
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

    public function accounts()
    {
        return $this->hasMany(Account::class);
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

    // use in crud field
    public function getOptionLabelAttribute()
    {
        $type = $this->plannedApplicationType->name;
        
        $type = explode(' / ', $type);

        if (is_array($type)) {
            $type = $type[0];
        }

        return $type .' :   '.$this->mbps . 'Mbps ----- ' . $this->currencyFormatAccessor($this->price);
    }
    
    // check custom field select_grouped_planned_application / AccountCrudController
    public function getDataLocationAttribute()
    {
        return $this->location->name;
    }


    public function getColumnDisplayAttribute()
    {
        return $this->location->name . ' - '. $this->optionLabel;
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
