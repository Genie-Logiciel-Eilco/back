<?php


namespace App\Http\Controllers;

use App\Models\Book;

class UserController extends Controller
{
    public function addBookToFav($uuid)
    {
        $book=Book::find($uuid);
        if($book)
        {
            $this->getAuthenticatedUser()->books()->syncWithoutDetaching($book);
            return $this->sendResponse("Success");
        }
        return $this->sendError("Book with id #{$uuid} not found");

    }
    public function removeBookFromFav($uuid)
    {
        $book=Book::find($uuid);
        if($book)
        {
            if($this->getAuthenticatedUser()->books()->where("books.id",$uuid)->first())
            {
                $this->getAuthenticatedUser()->books()->detach($book);
                return $this->sendResponse("Success");
            }
            return $this->sendError("Book with id #{$uuid} not found in favorites");
        }
        return $this->sendError("Book with id #{$uuid} not found");
    }
    public function getFav()
    {
        return $this->sendResponse($this->getAuthenticatedUser()->books()->get());
    }
}
?>