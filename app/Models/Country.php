<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory;

    public function states()
    {
        return $this->hasMany(State::class, 'country_guid', 'country_guid');
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'country_guid', 'country_guid');
    }
}
