<?php


namespace App\Http\Controllers;

use App\Http\Requests\AddBookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function uploadImage($uuid=null,Request $request)
    {
        if (!$this->hasRole("ROLE_ADMIN")) {
            return $this->permissionDenied();
        }
        $file = $request->file("File");
        if ($file !== null) {
            $extension=$file->getClientOriginalExtension();
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
                    // Storage::disk('ftp')->put('images/'.$book->id.".".$extension, fopen($request->file('File'), 'r+'));

                    return $this->sendResponse(["id"=>$book->id,"extension"=>$extension],"Image uploaded successfully");
                }
                elseif(Book::where("id",$uuid)->first())
                {
                    $file->move(public_path("images"),$uuid.".".$extension);
                    // Storage::disk('ftp')->put('images/'.$uuid.".".$extension, fopen($request->file('File'), 'r+'));               
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
            $extension=$file->getClientOriginalExtension();
        }
        else
        {
            return $this->sendError("No file found");
        }
        $allowed_extension = array('epub');
        if (in_array($extension, $allowed_extension)) {
            if($file->getSize()<20000000)
            {
                if($uuid==null)
                {
                    $book=Book::create();
                    Book::where("id",$book->id)->update(["fileLocation"=>$book->id.".".$extension]);
                    $file->move(public_path("files"),$book->id.".".$extension);
                    // Storage::disk('ftp')->put('books/'.$book->id.'/'.$book->id.".".$extension, fopen($request->file('File'), 'r+'));;
                    return $this->sendResponse(["id"=>$book->id],"File uploaded successfully");
                }


                elseif(Book::where("id",$uuid)->first())
                {
                    $file->move(public_path("files"),$uuid.".".$extension);
                    // Storage::disk('ftp')->put('books/'.$uuid.'/'.$uuid.".".$extension, fopen($request->file('File'), 'r+'));
					// if(Storage::disk('ftp')->exists('books/'.$uuid.'/'.$uuid.".mp3")) {
					
					// 	Storage::disk('ftp')->delete('books/'.$uuid.'/'.$uuid.".mp3");
							                    	
					// }
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
            $array3=[];
            foreach($book['authors'] as $author)
            {
                $array3= ['id' => $author->id, 'first_name' => $author->first_name,'last_name' => $author->last_name];
                array_push($array,$array3);
                
            }
            foreach($book['categories'] as $category)
            {
                array_push($array2,$category);
            }
            unset($book->authors);
            unset($book->categories);
            $book['authors']=$array;
            $book['categories']=$array2;
            return $this->sendResponse($book,"Success");
        }
        else
        {
            return $this->sendError("Book with uuid {$uuid} was not found");
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
            $array3=[];
            foreach($book['authors'] as $author)
            {
                $array3= ['id' => $author->id, 'first_name' => $author->first_name,'last_name' => $author->last_name];
                array_push($array,$array3);
            }
            foreach($book['categories'] as $category)
            {
                array_push($array2,$category);
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
            $array3=[];
            foreach($book['authors'] as $author)
            {
                $array3= ['id' => $author->id, 'first_name' => $author->first_name,'last_name' => $author->last_name];
                array_push($array,$array3);
            }
            foreach($book['categories'] as $category)
            {
                array_push($array2,$category);
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
    public function search($rowsPerPage=10,Request $request)
    {
        if(!$this->hasRole("ROLE_ADMIN"))
        {
            return $this->permissionDenied();
        }
        $query = Book::query();
        $columns = ['isbn','name','synopsis','publicationDate'];
        if($request->except('_token')!=null)
        {
            foreach($request->except('_token') as $key=>$value)
            {
                if($key=="all")
                {
                    foreach($columns as $column){
                        $query->orWhere(DB::raw('lower('.$column.')') , 'LIKE', '%' . strtolower($value) . '%');
                    }
                }
                elseif($key=="author")
                {
                    $books=array();
                    $authors=Author::where(DB::raw('lower(first_name)'),'LIKE','%'.strtolower($value).'%')
                    ->orWhere(DB::raw('lower(last_name)'),'LIKE','%'.strtolower($value).'%')->get();

                    foreach($authors as $author)
                    {
                        foreach($author->books()->get() as $book)
                        {
                            if($book->isReady)
                            {
                            unset($book->pivot);
                            array_push($books,$book);
                            }
                        }
                    }
                    $books=$this->my_array_unique($books);
                    foreach($books as $book)
                    {

                        $array=[];
                        $array2=[];
                        $array3=[];
                        foreach($book['authors'] as $author)
                        {
                            $array3= ['id' => $author->id, 'first_name' => $author->first_name,'last_name' => $author->last_name];
                            array_push($array,$array3);
                        }
                        foreach($book['categories'] as $category)
                        {
                            array_push($array2,$category);
                        }
                        unset($book->authors);
                        unset($book->categories);
                        $book['authors']=$array;
                        $book['categories']=$array2;
                    }
                    if($request->paginate)
                    {
                    $data=$this->paginateArray($books,$rowsPerPage);
                    }
                    else
                    {
                        $data=array_slice($books, 0, 10);
                    }
                    return $this->sendResponse($data,"Success");
                }
                elseif($key=="publisher")
                {
                    $books=array();
                    $publishers=Publisher::where(DB::raw('lower(name)'),'LIKE','%'.strtolower($value).'%')->get();
                    foreach($publishers as $publisher)
                    {
                        foreach($publisher->books()->get() as $book)
                        {
                            if($book->isReady)
                            {
                            array_push($books,$book);
                            }
                        }
                    }
                    foreach($books as $book)
                    {

                        $array=[];
                        $array2=[];
                        $array3=[];
                        foreach($book['authors'] as $author)
                        {
                            $array3= ['id' => $author->id, 'first_name' => $author->first_name,'last_name' => $author->last_name];
                            array_push($array,$array3);
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
                    if($request->paginate)
                    {
                    $data=$this->paginateArray($books,$rowsPerPage);
                    }
                    else
                    {
                        $data=array_slice($books, 0, 10);
                    }
                    return $this->sendResponse($data,"Success");
                }
                elseif($key="category")
                {
                    $books=array();
                    $categories=Category::where(DB::raw('lower(name)'),'LIKE','%'.strtolower($value).'%')->get();
                    foreach($categories as $category)
                    {
                        foreach($category->books()->get() as $book)
                        {
                            unset($book->pivot);
                            array_push($books,$book);
                        }
                    }
                    $books=$this->my_array_unique($books);
                    foreach($books as $book)
                    {

                        $array=[];
                        $array2=[];
                        $array3=[];
                        foreach($book['authors'] as $author)
                        {
                            $array3= ['id' => $author->id, 'first_name' => $author->first_name,'last_name' => $author->last_name];
                            array_push($array,$array3);
                        }
                        foreach($book['categories'] as $category)
                        {
                            array_push($array2,$category);
                        }
                        unset($book->authors);
                        unset($book->categories);
                        $book['authors']=$array;
                        $book['categories']=$array2;
                    }

                    if($request->paginate)
                    {
                    $data=$this->paginateArray($books,$rowsPerPage);
                    }
                    else
                    {
                        $data=array_slice($books, 0, 10);
                    }
                    return $this->sendResponse($data,"Success");
                }
                elseif( in_array($key,$columns))
                {
                    $query->orWhere(DB::raw('lower('.$key.')'), 'LIKE', '%' . strtolower($value) . '%');
                }
                else
                {
                    return $this->sendError("Key not allowed");
                }
                if($request->paginate)
                {
                    $books=$query->where('isReady',1)->paginate();
                }
                else
                {
                    $books=$query->where('isReady',1)->take(10)->get();
                }

                foreach($books as $book)
                {
                    $array=[];
                    $array2=[];
                    $array3=[];
                    foreach($book['authors'] as $author)
                    {
                        $array3= ['id' => $author->id, 'first_name' => $author->first_name,'last_name' => $author->last_name];
                        array_push($array,$array3);
                    }
                    foreach($book['categories'] as $category)
                    {
                        array_push($array2,$category);
                    }
                    unset($book->authors);
                    unset($book->categories);
                    $book['authors']=$array;
                    $book['categories']=$array2;
                }

                return $this->sendResponse($books,"Success 2");
            }

        }
    }
    public function paginateArray($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator ::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator ($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    function my_array_unique($array){
        $tmps = array();

        foreach ($array as $object){
            $flag=false;
            foreach($tmps as $tmp)
            {
                if ($tmp->id==$object->id)
                {
                    $flag=true;
                }
            }
            if(!$flag)
            {
                array_push($tmps,$object);
            }
        }
        return $tmps;
    }
}
