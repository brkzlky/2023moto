<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function sender()
    {
        return $this->hasOne(User::class, 'user_guid', 'user_own_guid');
    }

    public function receiver()
    {
        return $this->hasMany(User::class, 'user_guid', 'user_opposite_guid');
    }
}
