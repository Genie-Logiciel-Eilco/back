<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        Category::create([
            'name'=>"Politique"
        ]);
        Category::create([
            'name'=>"Education"
        ]);
        Category::create([
            'name'=>"Histoire"
        ]);
        Category::create([
            'name'=>"Psychologie"
        ]);
        Category::create([
            'name'=>"Philosophie"
        ]);
        Category::create([
            'name'=>"Santé"
        ]);
        Category::create([
            'name'=>"Science"
        ]);
        Category::create([
            'name'=>"Economie"
        ]);      
    }
}