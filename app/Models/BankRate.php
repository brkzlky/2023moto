<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankRate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table="bank_rates";

    public function bank()
    {
        return $this->hasOne(Bank::class,'bank_guid','bank_guid');
    }
}
