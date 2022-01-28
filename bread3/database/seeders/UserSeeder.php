<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new  User();
        $user->name = "Admin";
        $user->email = "admin@gmail.com";
        $user->password = Hash::make("admin");
        $user->role_id = 1;
        $user->save();


        $user = new User();
        $user->name = 'Anh DUC';
        $user->email = 'trananhducty@gmail.com';
        $user->password= Hash::make("123456");
        $user->role_id = 2;
        $user->save();


    }
}
