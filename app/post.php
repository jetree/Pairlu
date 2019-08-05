<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    protected $fillable = ['user_id','todo','friend_id','status'];

    public function user(){
      return $this->belongsTo('App\User');
    }
}
