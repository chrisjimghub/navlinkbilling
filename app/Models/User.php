<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Customer;
use App\Models\PisoWifiCollector;
use App\Models\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Backpack\CRUD\app\Models\Traits\CrudTrait; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasRoles;
    use CrudTrait;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'theme' => 'array',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        // Attach an event listener for the 'saved' event
        static::saved(function ($user) {
            // Check if email was changed and customer_id is not null
            if ($user->isDirty('email') && !is_null($user->customer_id)) {
                // Find the associated customer
                $customer = $user->customer;

                if ($customer) {
                    // Update the customer's email to match the user's email
                    $customer->email = $user->email;
                    $customer->save();
                }
            }
        });
    }

    public function isCustomer()
    {
        return $this->customer_id !== null;
    }

    public function isAdmin()
    {
        return $this->customer_id == null;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function pisoWifiCollectors()
    {
        return $this->belongsToMany(PisoWifiCollector::class, 'piso_wifi_user')->withTimestamps();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    // In User model
    public function scopeRoleCollectors($query)
    {
        return $query->role('collector');
    }


    public function scopeAdminUsersOnly(Builder $query, $withEmailContainWith = null)
    {
        if ($withEmailContainWith) {
            $query->where('email', 'NOT LIKE', "%{$withEmailContainWith}%");
        }

        return $query->whereNull('customer_id');
    }
    
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
    /**
     * Set the customer's ID.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setCustomerIdAttribute($value)
    {
        // sync User email and customer email.

        // Example custom logic
        if ($value == 1) {
            // Do something specific when setting customer_id to 1
        }

        // Set the attribute value
        $this->attributes['customer_id'] = $value;
    }
}
