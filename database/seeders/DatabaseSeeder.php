<?php

namespace Database\Seeders;

use App\Models\AddressUser;
use App\Models\ProfileUser;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    User::create([
      'username' => 'admin',
      'email' => 'admin@gmail.com',
      'password' => '123456'
    ]);

    $roles = [
      'guest', 'admin', 'instructor', 'student'
    ];

    foreach ($roles as $role) {
      Role::create([
        'name' => $role
      ]);
    }

    $user = User::first();
    $profile = new ProfileUser();
    $profile->user()->associate($user);
    $profile->save();

    $address = new AddressUser();
    $address->user()->associate($user);
    $address->save();

    $role = Role::where('name', 'admin')->first();
    $user->roles()->attach($role);
  }
}
