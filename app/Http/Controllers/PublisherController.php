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
}