<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    public function languages()
    {
        return $this->belongsToMany('App\Language');
    }

    public function genres()
    {
        return $this->belongsToMany('App\Genre');
    }
}
