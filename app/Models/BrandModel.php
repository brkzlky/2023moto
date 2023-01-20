<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrandModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "models";
    public function model_trims()
    {
        return $this->hasMany(ModelTrim::class, 'model_guid', 'model_guid');
    }
}
