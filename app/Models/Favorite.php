<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Favorite extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function listing()
    {
        return $this->hasOne(Listing::class, 'listing_guid','listing_guid');
    }
}
