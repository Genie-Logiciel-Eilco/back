<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    public function getById($id)
    {
        $author=Category::findOrFail($id);
        return $this->sendResponse($author);
    }
    public function getAll()
    {
        return $this->sendResponse(Category::all());
    }
    public function paginate($rowsPerPage=10)
    {
        return $this->sendResponse(Category::paginate($rowsPerPage),"Success");
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        if (!$this->hasRole("ROLE_ADMIN")) {
            return $this->permissionDenied();
        }
        $fields=$request->validate([
            "name"=>'required|string|unique:categories'
        ]);
        Category::create($fields);
        return $this->sendResponse([],"Success");
    }

    
    public function update($id,Request $request)
    {
        if (!$this->hasRole("ROLE_ADMIN")) {
            return $this->permissionDenied();
        }
        $fields=$request->validate(
            [
                "name"=>"required|string"
            ]
        );
        $category2=Category::where(DB::raw('LOWER(`name`)'),strtolower(trim($fields['name'])))->first();
        $category=Category::find($id);
        if($category)
        {
            if($category2 && $category2->id !=$category->id)
            {
              return $this->sendError("Name of category already exists");  
            }
            $category->update($fields);
            return $this->sendResponse($category,"Category updated Successfully");
        }
    }
    public function delete($id)
    {
        if (!$this->hasRole("ROLE_ADMIN")) {
            return $this->permissionDenied();
        }
        $category=Category::find($id);
        if($category)
        {
            $category->delete();
            return $this->sendResponse([],"Deleted successfully");
        }
        else
        {
            return $this->sendError("Category with id {$id} was not found");
        }
    }
    public function getBooksByCategory($id)
    {
        if (!$this->hasRole("ROLE_ADMIN")) {
            return $this->permissionDenied();
        }
        $category=Category::where("id",$id)->first();
        if(!$category)
        {
            return $this->sendError("Category with id {$id} was not found");
        }
        $books=$category->books()->get();
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
            unset($book->pivot);
            $book['authors']=$array;
            $book['categories']=$array2;                                
        }
        return $this->sendResponse($books);

    }
}
