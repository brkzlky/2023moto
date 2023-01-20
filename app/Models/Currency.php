<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table="currencies";

    public function rates()
    {
        return $this->hasMany(ExchangeRate::class,'from','currency_guid')->orderBy('id','DESC')->take(5);
    }
    public function rates_exchange()
    {
        return $this->hasMany(ExchangeRate::class,'from','currency_guid')->orderBy('validity_date','ASC')->take(30);
    }
  
}
