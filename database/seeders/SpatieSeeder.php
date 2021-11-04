<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SpatieSeeder extends Seeder
{
    /**
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name'=>"ROLE_ADMIN"]);
        Role::create(['name'=>"ROLE_USER"]);
    }
}