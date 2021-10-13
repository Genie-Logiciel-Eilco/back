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
        $book=Book::create([
            'isbn'=>"B329832",
            'name'=>"buya",
            'subject'=>"Comedie",
            'synopsis'=>"Bruh what the hell man",
            'fileLocation'=>"\/buya\/xd.txt",
            'imageLocation'=>"\/buya\/xd.jpg",
            'publicationDate'=> date("F j, Y, g:i a"),
            'counter'=>3
        ]);
    }
}
