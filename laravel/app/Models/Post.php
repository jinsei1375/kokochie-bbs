<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{

    use SoftDeletes;
    // protected $table = 'posts';

    public function user() {
        return $this->belongsTo('App\User');
    }
    public function favoriteusers() {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
    public function likes() {
        return $this->hasMany('App\Models\Like');
    }
    public function comments() {
        return $this->hasMany('App\Models\Comment');
    }
}
