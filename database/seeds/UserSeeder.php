<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        foreach (config('users') as $user) {
            $newUser = new User();
            $newUser->photo = $user['photo'];
            $newUser->name = $user['name'];
            $newUser->slug = Str::slug("$newUser->name", '-');
            $newUser->email = $user['email'];
            $newUser->password = Hash::make($user['password']);
            $newUser->address = $user['address'];
            $newUser->phone = $user['phone'];
            $newUser->p_iva = $user['p_iva'];

            $newUser->save();
        }
    }
}
