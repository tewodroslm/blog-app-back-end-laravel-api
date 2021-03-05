<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Content;
use Illuminate\Support\Facades\Auth;

class ContentController extends Controller
{
    private $sucess_status = 200;
    
    //CREATE A CONTENT // UPDATE YOUR CONTENT
    public function createContent(Request $request){
        $user = Auth::user();
        $validator = $request->validate([
            'title' => 'required|string',
            'content' => 'required',
        ]);

        $content_array = array(
            "title" => $request->title,
            "content" => $request->content,
            "user_id" => $user->id 
        );
        $blog_id = $request->id;

        if($blog_id != ""){
            $status = Content::where("id", $blog_id)->update($content_array);
            if($status == 1){
                return response()->json([ "message" => "blog updated successfully", "data" => $content_array]);
            }else {
                return response()->json(["message" => "blog not updated"]);
            }
        }

        $content = Content::create($content_array);

        if(!is_null($content)) {
            return response()->json(["success" => true,"title" => $request->get('title') ,"data" => $content]);
        }else {
            return response()->json(["message" => "Whoops! content not created."]);
        }

    }

    //GET(DISPLAY) ALL THE CONTENTS
    public function allContents(){

        $contents = array();
        $user = Auth::user();
        $contents = Content::all();
        if(count($contents) > 0){
            return response()->json(["count" => count($contents), "data" => $contents]);
        } else {
            return response()->json(["message" => "Whoops! no content found"]);
        }
      
    }

    //DELETE YOUR OWN CONTENT ONLY
    public function deleteContent($content_id){
        if($content_id == 'undefined' || $content_id == ""){
            return response()->json(["message" => "Alert! enter the content id"]);
        }

        $content = Content::find($content_id);
        if(!is_null($content)){
            $delete_content = Content::where("id", $content_id)->delete();
        }
    }

    //GET YOU OWN CONTENT ONLY
    public function ownedPosts(){
        $contents = Content::all();
        $user = Auth::user();
        $personalContent = [];
        for($i=0; $i<count($contents); $i++){
            if($contents[$i]->user_id == $user->id){
                array_push($personalContent,$contents[$i]);
            }
        }
        if(count($personalContent) > 0){
            return response()->json(["count" => count($personalContent), "data" => $personalContent]);
        } else {
            return response()->json(["status" => "failed", "message" => "Whoops! no posts found"]);
        }
    }
}
