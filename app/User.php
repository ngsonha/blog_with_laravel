<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserRole;
class User extends Authenticatable
{

   // use Authenticatable, CanResetPassword;

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'users';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name', 'email', 'password'];

  /**
   * The attributes excluded from the model's JSON form.
   *
   * @var array
   */
  protected $hidden = ['password', 'remember_token'];

  // user has many posts
  public function posts()
  {
    return $this->hasMany('App\Posts', 'author_id');
  }

  // user has many comments
  public function comments()
  {
    return $this->hasMany('App\Comments', 'from_user');
  }

  public function can_post()
  {
    $role = $this->role;
    if ($role == UserRole::author || $role == UserRole::admin) {
      return true;
    }
    return false;
  }

  public function is_admin()
  {
    $role = $this->role;
    if ($role == UserRole::admin) {
      return true;
    }
    return false;
  }

}