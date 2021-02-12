<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Redis;

class PostController extends Controller
{
    public function index()
    {
        return Post::all();
    }

    public function store(Request $request)
    {
        $request['user_id'] = auth()->user()->id;
        $post = Post::create($request->all());
        $this->updateUserPostRedis(auth()->user()->id);
        $allPosts = Post::all();        
        return $post;
    }

    public function show($id)
    {
        if (is_null($id)) 
        {
            $post = Redis::get('user_post/' . auth()->user()->id);
        } else 
        {
            $post = Post::Find($id);
        }
        if ($post) 
        {
            return $post;
        } 
        else 
        {
            return response()->json(['error' => "Post not found"], 404);
        }

    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if ($post) 
        {
            if (auth()->user()->id == $post->user_id) 
            {
                $post->update($request->all());
                $this->updateUserPostRedis(auth()->user()->id);
                return $post;
            } 
            else 
            {
                return response()->json(['error' => "You can't update someone else's post"], 400);
            }
        } 
        else 
        {
            return response()->json(['error' => "Post not found"], 404);
        }

    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if ($post) 
        {
            if (auth()->user()->id == $post->user_id) 
            {
                $post = Post::destroy($id);
                $allPosts = Post::all();
                $this->deleteUserPostRedis(auth()->user()->id);                
                return $post;
            } 
            else 
            {
                return response()->json(['error' => "You cant delete someone else`s post"], 400);
            }
        } 
        else 
        {
            return response()->json(['error' => "Post not found"], 404);
        }

    }
}
