<?php

namespace Database\Seeders;

use App\Models\Role;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     *
     * @return void
     */
    public function run()
    {
        Role::create(['role_name'=>"ROLE_ADMIN"]);
        Role::create(['role_name'=>"ROLE_USER"]);
    }
}