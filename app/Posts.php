<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $guarded = [];

  public function author()
  {
    return $this->belongsTo('App\User', 'author_id');
  }

  public function scopeGetAllPost($query){
    return $query->where('active', '1');
  }
  public function scopeGetPost($query,$sl){
    return $query->where('slug', $sl);
  }
  public function scopeUserAllPost($query,$userid){
    return $query->where('author_id',$userid);
  }
  public function scopeUserPost0($query,$userid){
    return $query->where('author_id', $userid)->where('active', '0');
  }
  public function scopeUserPost1($query,$userid){
    return $query->where('author_id', $userid)->where('active', '1');
  }
}