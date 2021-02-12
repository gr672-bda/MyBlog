<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comments;
use App\Models\Post;

class CommentController extends Controller
{
    public function index()
    {
        return Comments::all();
    }

    public function store(Request $request)
    {
        $blacklist = Post::query()
            ->join('blacklists', 'posts.user_id', '=', 'blacklists.user_id')
            ->where('blacklists.blocked_user_id', auth()->user()->id)
            ->get();
        if (count($blacklist)) 
        {
            return response()->json(['error' => "Cant add the comment, you are in blacklist"], 400);

        } 
        else 
        {
            $request['user_id'] = auth()->user()->id;
            $comment = Comments::create($request->all());
            $allComment = Comments::all();            
            return $comment;
        }

    }

    public function show($id)
    {
        $comment = Comments::find($id);
        if ($comment) 
        {
            return $comment;
        } 
        else 
        {
            return response()->json(['error' => "Comment not found"], 404);
        }

    }

    public function update(Request $request, $id)
    {
        $comment = Comments::find($id);
        if ($comment) 
        {
            $comment->update($request->all());
            return $comment;
        } 
        else 
        {
            return response()->json(['error' => "Comment not found"], 404);
        }

    }

    public function destroy(Request $request, $id)
    {
        $comment = Comments::find($id);
        if ($comment) 
        {
            if (auth()->user()->id == $comment->user_id) 
            {
                $comment = Comments::destroy($id);
                $allComment = Comments::all();                
                return $comment;
            } 
            else 
            {
                return response()->json(['error' => "You cant delete someone else's comment"], 400);
            }
        } 
        else 
        {
            return response()->json(['error' => "Comment not found"], 404);
        }

    }
}
