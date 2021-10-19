<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function getById($id)
    {
        $author=Author::findOrFails($id);
        return $this->sendResponse($author);
    }
    public function getAll()
    {
        return $this->sendResponse(Author::all());
    }
    public function add(AddAuthorRequest $request)
    {
        $fields=$request->validated();
        $author=Author::create($fields);
        return $this->sendResponse([$author],"Author added Successfully");
    }
    public function update($id,UpdateAuthorRequest $request)
    {
        $fields=$request->validated();
        $author=Author::where('id',$id)->update($fields);
        return $this->sendResponse([$author],"Author updated Successfully");
    }
    public function delete($id)
    {
        $author=Author::deleteById($id);
        if($author)
        {
            return $this->sendResponse([],"Deleted successfully");
        }
        else
        {
            return $this->sendError("Author with id {$id} was not found");
        }
    }
}