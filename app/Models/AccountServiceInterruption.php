<?php

namespace App\Models;

use App\Models\Model;
use App\Models\Account;
use App\Events\BillProcessed;
use Illuminate\Support\Carbon;


class AccountServiceInterruption extends Model
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $dispatchesEvents = [
        'created' => BillProcessed::class,
        'updated' => BillProcessed::class,
        'deleted' => BillProcessed::class,
    ];


    protected $table = 'account_service_interruptions';
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
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeOverlap($query, $date_start ,$date_end) 
    { 
        return $query->whereBetween('date_start', [$date_start, $date_end]) 
            ->orWhereBetween('date_end', [$date_start, $date_end]) 
            ->orWhereRaw('? BETWEEN date_start and date_end', [$date_start]) 
            ->orWhereRaw('? BETWEEN date_start and date_end', [$date_end]); 
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getTotalDaysAttribute()
    {
        if ($this->date_start && $this->date_end) {
            $start = Carbon::parse($this->date_start);
            $end = Carbon::parse($this->date_end);
            return $start->diffInDays($end);
        }
        return 0;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
