<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory,Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'isbn',
        'name',
        'subject',
        'synopsis',
        'fileLocation',
        'imageLocation',
        'publicationDate',
        'counter',
        'isReady',
        'publisher_id'
    ];
    public function authors()
    {
   //return $this->belongsToMany(RelatedModel, pivot_table_name, foreign_key_of_current_model_in_pivot_table, foreign_key_of_other_model_in_pivot_table);
   return $this->belongsToMany(
        Author::class,
        'authors_books',
        'book_id',
        'author_id');
    }
    public function publisher(){
        return $this->belongsTo(Publisher::class);
    }
    public function categories()
    {
   //return $this->belongsToMany(RelatedModel, pivot_table_name, foreign_key_of_current_model_in_pivot_table, foreign_key_of_other_model_in_pivot_table);
   return $this->belongsToMany(
        Category::class,
        'categories_books',
        'book_id',
        'category_id');
    }
}
