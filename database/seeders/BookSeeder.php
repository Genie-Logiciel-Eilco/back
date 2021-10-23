<?php

namespace Database\Seeders;

use App\Models\Book;

use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
            Book::create([
            'isbn'=>"B329832",
            'name'=>"buya",
            'subject'=>"Comedie",
            'synopsis'=>"Bruh what the hell man",
            'fileLocation'=>"/buya/xd.txt",
            'imageLocation'=>"/buya/xd.jpg",
            'publicationDate'=>date('Y-m-d ', strtotime("2020-11-05 ")),
            'counter'=>3,
            'isReady'=>1,
            'publisher_id'=>1
        ]);
    }
}
