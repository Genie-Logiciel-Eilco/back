<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    /**
    *
    * @return void
    */
    public function run()
    {
        $user=User::create([
            'first_name'=>"Errazgouni",
            'last_name'=>"Saad",
            'username'=>"bobhhy",
            'email'=>"saaderraz99@gmail.com",
            'password'=>bcrypt("SE300799"),
            'role_id'=>1
        ]);
        $user2=User::create([
            'first_name'=>"Aymane",
            'last_name'=>"El Mouhtarim",
            'username'=>"ceh",
            'email'=>"aymane.elmouhtarim@gmail.com",
            'password'=>bcrypt("SE300799"),
            'role_id'=>2
        ]);
    }
        
}
