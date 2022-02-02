<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use Validator;
use App\Models\Blog;
use App\Http\Resources\Blog as BlogResource;

class BlogController extends BaseController{
    public function index(){
        $blog = Blog::all();
        //dd($blog);
        return $this->sendResponse(BlogResource::collection($blog),"Posztok betöltve");
    }
    public function store(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            "title" => "required",
            "description" => "required"
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());
        }
        $blog = Blog::create($input);
        return $this->sendResponse(new BlogResource($blog), "Poszt kiírva");
    }
    public function show($id){
        $blog = Blog::find($id);
        if( is_null($blog)){
            return $this->sendError("Nincs ilyen poszt");
        }
        return $this->sendResponse(new BlogResource($blog), "Poszt betöltve");
    }
    public function update(Request $request,Blog $blog ) {
        $input = $request->all();
        $validator = Validator::make($input, [
            "title" => "required",
            "description" => "required",
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());
        }
        $blog->title = $input["title"];
        $blog->description = $input["description"];
        $blog->save();
        return $this->sendResponse(new BlogResource($blog), "Poszt módosítva");
    }
    public function destroy($id){
        Blog::destroy($id);
        return $this->sendResponse([], "Poszt törölve");
    }
}
