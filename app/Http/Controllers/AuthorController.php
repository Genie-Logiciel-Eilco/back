<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthorController extends Controller
{
    public function getById($id)
    {
        $author=Author::findOrFail($id);
        return $this->sendResponse($author);
    }
    public function getAll()
    {
        return $this->sendResponse(Author::all());
    }
    public function paginate($rowsPerPage=10)
    {
        return $this->sendResponse(Author::paginate($rowsPerPage),"Success");
    }
    public function add(AddAuthorRequest $request)
    {
        if (!$this->hasRole("ROLE_ADMIN")) {
            return $this->permissionDenied();
        }
        $fields=$request->validated();
        $fields["birthDate"]=date('Y-m-d ', strtotime($fields['birthDate']));
        if(isset($fields["deathDate"]))
        {
            $fields["deathDate"]=date('Y-m-d ', strtotime($fields['deathDate']));
        }
        $author=Author::create($fields);
        return $this->sendResponse($author,"Author added Successfully");
    }
    public function update($id,UpdateAuthorRequest $request)
    {
        if (!$this->hasRole("ROLE_ADMIN")) {
            return $this->permissionDenied();
        }
        $fields=$request->validated();
        $author=Author::find($id);
        if($author)
        {
            if($fields["birthDate"])
            {
            $fields["birthDate"]=date('Y-m-d ', strtotime($fields['birthDate']));
            }
            if(isset($fields["deathDate"]))
            {
            $fields["deathDate"]=date('Y-m-d ', strtotime($fields['deathDate']));
            }
            $author->update($fields);
            return $this->sendResponse($author,"Author updated Successfully");
        }
        else
        {
            return $this->sendError("Author with id {$id} was not found");
        }
        
    }
    public function uploadImage($id,Request $request)
    {
        if (!$this->hasRole("ROLE_ADMIN")) {
            return $this->permissionDenied();
        }
        $file = $request->file("File");
        if ($file !== null) {
            $extension=$file->guessExtension();
        }
        else
        {
            return $this->sendError("No File was found");
        }
        $allowed_extension = array('tif', 'jpeg', 'jpg','png');  
        if (in_array($extension, $allowed_extension)) {
            if($file->getSize()<2000000)
            {
               if(Author::where("id",$id)->first())
                {
                    $file->move(public_path("images/authors"),$id.".".$extension);
                    // Storage::disk('ftp')->put('books/'.$id.".".$extension, fopen($request->file('File'), 'r+'));;
                    Author::where("id",$id)->update(["imageLocation"=>$id.".".$extension]);
                    return $this->sendResponse(["id"=>$id,"extension"=>$extension],"Image uploaded successfully");
                }
                else
                {
                    return $this->sendError("Uuid doesn't exist"); 
                }
                
            }
            else
            {
                return $this->sendError("Image too large");
            }
            
        }
        else
        {
            return $this->sendError("Extension not allowed");
        }
    }
    public function delete($id)
    {
        if (!$this->hasRole("ROLE_ADMIN")) {
            return $this->permissionDenied();
        }
        $author=Author::find($id);
        if($author)
        {
            $author->delete();
            return $this->sendResponse([],"Deleted successfully");
        }
        else
        {
            return $this->sendError("Author with id {$id} was not found");
        }
    }
    public function getBooksByAuthor($id)
    {
        $author=Author::where('id',$id)->first();
        if($author)
        {
            $books=$author->books()->get();
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
        else
        {
            return $this->sendError("Author with id {$id} was not found");
        }

    }
}