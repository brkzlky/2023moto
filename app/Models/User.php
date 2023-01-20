<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    protected $table="users";

    use SoftDeletes;
    public function listings()
    {
        return $this->hasMany(Listing::class, 'user_guid', 'user_guid');
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'country_guid', 'country_guid');
    }
    public function country_api()
    {
        return $this->hasOne(Country::class, 'country_guid', 'country_guid')->select('country_guid','name');
    }

    public function type()
    {
        return $this->belongsTo(UserType::class, 'type_guid', 'type_guid');
    }

    public function type_api()
    {
        return $this->belongsTo(UserType::class, 'type_guid', 'type_guid')->select('type_guid', 'name_en','name_ar');
    }

    public function favorite()
    {
        return $this->belongsTo(Favorite::class, 'user_guid', 'user_guid');
    }
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'token',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
