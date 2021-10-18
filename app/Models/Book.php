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
        'isReady'
    ];

}
