<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserChat extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function messages_info()
    {
        return $this->hasMany(Message::class, 'user_chat_guid', 'user_chat_guid');
    }

    public function sender()
    {
        return $this->hasOne(User::class, 'user_guid', 'user_own_guid');
    }

    public function receiver()
    {
        return $this->hasOne(User::class, 'user_guid', 'user_opposite_guid');
    }

    public function listing_info()
    {
        return $this->hasOne(Listing::class, 'listing_guid', 'listing_guid');
    }

    public function last_message()
    {
        return $this->hasOne(Message::class, 'user_chat_guid', 'user_chat_guid')->orderBy("send_time","desc");
    }

}
