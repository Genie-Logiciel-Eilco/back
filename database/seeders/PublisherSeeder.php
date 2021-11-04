<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
            Publisher::create([
            'name'=>"Hachette",
            'description'=>"Test me",
            'foundationDate'=>"1920-08-12"
        ]);
            Publisher::create([
            'name'=>"Hatier",
            'description'=>"Test me 2",
            'foundationDate'=>"1923-08-10"
        ]);
            Publisher::create([
            'name'=>"Annaja7",
            'description'=>"Test me 3",
            'foundationDate'=>"1922-08-02"
        ]);
            Publisher::create([
            'name'=>"Peace",
            'description'=>"Test me 4",
            'foundationDate'=>"1921-08-11"
        ]);
    }
}
