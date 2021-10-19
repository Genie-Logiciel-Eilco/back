<?php


namespace App\Http\Controllers;

use App\Http\Requests\AddBookRequest;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function uploadImage($uuid=null,Request $request)
    {
        $file = $request->file("File");
        $allowed_extension = array('tif', 'jpeg', 'jpg','png');
        $extension=$file->getClientOriginalExtension();
        if (in_array($extension, $allowed_extension)) {
            if($file->getSize()<2000000)
            {
                if($uuid==null)
                {
                    $book=Book::create();
                    Book::where("id",$book->id)->update(["imageLocation"=>$book->id.".".$extension]);
                    $file->move(storage_path("images"),$book->id);
                    return $this->sendResponse(["id"=>$book->id],"Image uploaded successfully");
                }
                elseif(Book::where("id",$uuid)->first())
                {
                    $file->move(storage_path("images"),$uuid.".".$extension);
                    Book::where("id",$uuid)->update(["imageLocation"=>$uuid.".".$extension]);
                    return $this->sendResponse(["id"=>$uuid],"Image uploaded successfully");
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
        $file = $request->file("File");
        $allowed_extension = array('pdf', 'odt', 'docx','txt');
        $extension=$file->getClientOriginalExtension();
        if (in_array($extension, $allowed_extension)) {
            if($file->getSize()<20000000)
            {
                if($uuid==null)
                {
                    $book=Book::create();
                    Book::where("id",$book->id)->update(["fileLocation"=>$book->id.".".$extension]);
                    $file->move(storage_path("files"),$book->id);
                    return $this->sendResponse(["id"=>$book->id],"File uploaded successfully");
                }
                elseif(Book::where("id",$uuid)->first())
                {
                    $file->move(storage_path("files"),$uuid.".".$extension);
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
        $fields=$request->validated();
        $book=Book::where('id',$uuid);
        if(Book::where('isbn',$fields['isbn'])->first() && $book->first()->isbn !=$fields['isbn'] )
        {
            return $this->sendError("Isbn already taken");
        }
        $book->update($fields);
        $book->update(['isReady'=>1]);
        return $this->sendResponse(['id'=>$uuid],"Updated successfully");
    }
    public function show($uuid)
    {
        $book=Book::where('id',$uuid)->first();
        if($book)
        {
            return $this->sendResponse($book,"Success");
        }
        else
        {
            return $this->sendError("Book with uuitd {$uuid} was not found");
        }
    }
    public function paginate($rowsPerPage=10)
    {
        return $this->sendResponse(Book::where('isReady',1)->paginate($rowsPerPage),"Success");
    }
    public function delete($uuid)
    {
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