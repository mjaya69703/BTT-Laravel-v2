<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Movies extends Model
{
    protected $guarded = [];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => url('storage/images/movies/' . $image),
        );
    }

    public function review(){
        return $this->hasMany(Reviews::class);
    }
}
