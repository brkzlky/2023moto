<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasFactory;
    public function user_type_listings()
    {
        return $this->hasManyThrough(
            Listing::class,
            User::class,
            'type_guid',
            'user_guid',
            'type_guid',
            'user_guid'
        );
    }
}
