<?php


namespace App\Http\Controllers;

use App\Http\Requests\AddPublisherRequest;
use App\Http\Requests\UpdatePublisherRequest;
use App\Models\Publisher;

class PublisherController extends Controller
{
    public function getById($id)
    {
        $author=Publisher::findOrFail($id);
        return $this->sendResponse($author);
    }
    public function getAll()
    {
        return $this->sendResponse(Publisher::all());
    }
    public function paginate($rowsPerPage=10)
    {
        return $this->sendResponse(Publisher::paginate($rowsPerPage),"Success");
    }
    public function add(AddPublisherRequest $request)
    {
        $fields=$request->validated();
        $publisher=Publisher::create($fields);
        return $this->sendResponse($publisher,"Publisher added Successfully");
    }
    public function update($id,UpdatePublisherRequest $request)
    {
        $fields=$request->validated();
        $publisher=Publisher::find($id);
        if($publisher)
        {
        $publisher->update($fields);
        return $this->sendResponse($publisher,"Publisher updated Successfully");
        }
        else
        {
            return $this->sendError("Publisher with id {$id} was not found");
        }
    }
    public function delete($id)
    {
        $publisher=Publisher::find($id);
        if($publisher)
        {
            $publisher->delete();
            return $this->sendResponse([],"Deleted successfully");
        }
        else
        {
            return $this->sendError("Publisher with id {$id} was not found");
        }
    }
    public function getBooksByPublisher($id)
    {
        $publisher=Publisher::find($id);
        if(!$publisher)
        {
            return $this->sendError("Publisher with id {$id} was not found");
        }
        $books=$publisher->books()->get();
        foreach($books as $book)
        {

            $array=[];
            $array2=[];
            foreach($book['authors'] as $author)
            {
                array_push($array,$author->id);
            }
            foreach($book['categories'] as $category)
            {
                array_push($array2,$category->id);
            }
            unset($book->authors);
            unset($book->categories);
            unset($book->pivot);
            $book['authors']=$array;
            $book['categories']=$array2;                                
        }
        return $this->sendResponse($books);

    }
}