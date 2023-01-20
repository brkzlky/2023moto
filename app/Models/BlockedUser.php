<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedUser extends Model
{
    use HasFactory;

    protected $table="blocked_user";
    
    public function owner()
    {
        return $this->hasOne(User::class,'user_guid','user_guid');
    }

    public function blocked()
    {
        return $this->hasOne(User::class,'user_guid','blocked_user_guid');
    }
}
