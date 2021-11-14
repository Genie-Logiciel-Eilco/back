<?php


namespace App\Http\Controllers;

use App\Http\Requests\AddBookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    public function uploadImage($uuid=null,Request $request)
    {
        if (!$this->hasRole("ROLE_ADMIN")) {
            return $this->permissionDenied();
        }
        $file = $request->file("File");
        if ($file !== null) {
            $extension=$file->guessExtension();
        }
        $allowed_extension = array('tif', 'jpeg', 'jpg','png');  
        if (in_array($extension, $allowed_extension)) {
            if($file->getSize()<2000000)
            {
                if($uuid==null)
                {
                    $book=Book::create();
                    Book::where("id",$book->id)->update(["imageLocation"=>$book->id.".".$extension]);
                    $file->move(public_path("images"),$book->id.".".$extension);
                    return $this->sendResponse(["id"=>$book->id,"extension"=>$extension],"Image uploaded successfully");
                }
                elseif(Book::where("id",$uuid)->first())
                {
                    $file->move(public_path("images"),$uuid.".".$extension);
                    Book::where("id",$uuid)->update(["imageLocation"=>$uuid.".".$extension]);
                    return $this->sendResponse(["id"=>$uuid,"extension"=>$extension],"Image uploaded successfully");
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
    public function uploadFile($uuid=null,Request $request)
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
            return $this->sendError("No file found");
        }
        $allowed_extension = array('pdf', 'odt', 'docx','txt');
        if (in_array($extension, $allowed_extension)) {
            if($file->getSize()<20000000)
            {
                if($uuid==null)
                {
                    $book=Book::create();
                    Book::where("id",$book->id)->update(["fileLocation"=>$book->id.".".$extension]);
                    $file->move(public_path("files"),$book->id.".".$extension);
                    return $this->sendResponse(["id"=>$book->id],"File uploaded successfully");
                }
                elseif(Book::where("id",$uuid)->first())
                {
                    $file->move(public_path("files"),$uuid.".".$extension);
                    Book::where("id",$uuid)->update(["fileLocation"=>$uuid.".".$extension]);
                    return $this->sendResponse(["id"=>$uuid],"File uploaded successfully");
                }
                else
                {
                    return $this->sendError("Uuid doesn't exist"); 
                }
            }
            else
            {
                return $this->sendError("File too large");
            }
        }
        else
        {
            return $this->sendError("Extension not allowed");
        }
    }
    public function addBook($uuid,AddBookRequest $request)
    {
        if (!$this->hasRole("ROLE_ADMIN")) {
            return $this->permissionDenied();
        }

        $fields=$request->validated();
        $authors=$fields['authors'];
        $categories=$fields['categories'];
        unset($fields['categories']);
        unset($fields['authors']);
        $book=Book::where('id',$uuid);
        if(Book::where('isbn',$fields['isbn'])->first() && $book->first()->isbn !=$fields['isbn'] )
        {
            return $this->sendError("Isbn already taken");
        }
        $book->update($fields);
        $book->update(['isReady'=>1]);
        foreach($authors as $author)
        {
            if(!Author::where('id',$author)->exists())
            {
                return $this->sendError("Author with id {$author} doesn't exist");
            }
        }
        foreach($categories as $category)
        {
            if(!Category::where('id',$category)->exists())
            {
                return $this->sendError("Category with id {$category} doesn't exist");
            }
        }
        $book->first()->authors()->sync($authors);
        $book->first()->categories()->sync($categories);       
        return $this->sendResponse(['id'=>$uuid],"Updated successfully");
    }
    public function show($uuid)
    {
        $book=Book::where('id',$uuid)->first();
        if($book)
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
            $book['authors']=$array;
            $book['categories']=$array2;
            return $this->sendResponse($book,"Success");
        }
        else
        {
            return $this->sendError("Book with uuitd {$uuid} was not found");
        }
    }
    public function paginate($rowsPerPage=10)
    {
        $data=Book::where('isReady',1)->paginate($rowsPerPage);
        foreach($data as $book)
        {
            if($book)
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
            $book['authors']=$array;
            $book['categories']=$array2;
            }
        }
        return $this->sendResponse($data,"Success");
    }
    public function getAll()
    {
        $books=Book::where('isReady',1)->get();
        foreach($books as $book)
        {
            if($book)
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
            $book['authors']=$array;
            $book['categories']=$array2;
            }
        }
        return $this->sendResponse($books,"Success");
    }
    public function delete($uuid)
    {
        if (!$this->hasRole("ROLE_ADMIN")) {
            return $this->permissionDenied();
        }
        if(Book::where('id',$uuid)->delete())
        {
            return $this->sendResponse([],"Deleted successfully");
        }
        else
        {
            return $this->sendError("Book with uuid {$uuid} was not found");
        }
    }
}