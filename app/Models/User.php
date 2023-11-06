<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;

  protected $guarded = ['id'];
  protected $with = ['profileUser', 'addressUser'];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
  ];

  /**
   * Get the profileUser associated with the User
   *
   * @return \Illuminate\Database\Eloquent\Relations\HasOne
   */
  public function profileUser(): HasOne
  {
    return $this->hasOne(ProfileUser::class);
  }

  /**
   * Get the addressUser associated with the User
   *
   * @return \Illuminate\Database\Eloquent\Relations\HasOne
   */
  public function addressUser(): HasOne
  {
    return $this->hasOne(AddressUser::class);
  }

  /**
   * Get the instructor associated with the User
   *
   * @return \Illuminate\Database\Eloquent\Relations\HasOne
   */
  public function instructor(): HasOne
  {
    return $this->hasOne(Instructor::class, 'user_id');
  }

  /**
   * The roles that belong to the User
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function roles(): BelongsToMany
  {
    return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
  }
}
