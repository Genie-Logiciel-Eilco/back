<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        Author::create([
            'first_name'=>"Victor",
            'last_name'=>"Hugo",
            'biography'=>"Victor Hugo is a French poet, playwright, writer, novelist and romantic designer, born February 26, 1802 in Besançon and died May 22, 1885 in Paris. He is considered to be one of the most important writers of the French language.",
            'birthDate'=>"1802-02-26",
            'deathDate'=>"1885-05-02",
            'birthplace'=>"Besançon,France",
        ]);
          
    }
}