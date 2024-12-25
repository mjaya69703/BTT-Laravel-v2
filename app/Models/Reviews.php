<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    protected $guarded = [];

    public function movies(){
        return $this->belongsTo(Movies::class, 'movie_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
