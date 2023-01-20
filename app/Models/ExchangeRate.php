<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExchangeRate extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function to_name()
    {
        return $this->hasOne(Currency::class,'currency_guid','to');
    }
    public function from_name()
    {
        return $this->hasOne(Currency::class,'currency_guid','from');
    }
}
