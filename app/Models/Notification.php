<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'notifications';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];

    protected $casts = [
        'id' => 'string',
        'data' => 'array',
    ];

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

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    /**
     * Scope a query to only include records where notifiable_type is 'App\Models\User' 
     * and notifiable_id is the ID of the currently authenticated user.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeForAuthenticatedUser(Builder $query): Builder
    {
        // Get the ID of the currently authenticated user
        $userId = Auth::id();

        // Filter the query
        return $query->where('notifiable_type', 'App\Models\User')
                     ->where('notifiable_id', $userId);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getTypeHumanReadableAttribute()
    {
        $type = $this->type;

        $type = str_replace('App\Notifications\\', '', $type);

        return strHumanReadable($type);
    }
    
    public function getMessageAttribute()
    {
        // Decode the JSON data into an associative array
        $data = json_decode($this->attributes['data'], true);

        // Check if JSON decoding was successful
        if (json_last_error() === JSON_ERROR_NONE && isset($data['message'])) {
            return $data['message']; // Return the 'message' part of the data
        }

        // Handle cases where JSON decoding fails or 'message' key does not exist
        return null; // Or you can return a default value or an error message
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function markAsRead()
    {
        $this->read_at = Carbon::now();
        $this->save();
    }
}
