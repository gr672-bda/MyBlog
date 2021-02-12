<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blacklist;

class BlacklistController extends Controller
{
    public function index()
    {
        return Blacklist::all();
    }

    public function store(Request $request)
    {
        $blockedUserId = $request->blocked_user_id;
        if (auth()->user()->id == $blockedUserId) 
        {
            return response()->json(['error' => "You can't add yourself to blacklist"], 400);
        } 
        else 
        {
            $request['user_id'] = auth()->user()->id;
            return Blacklist::create($request->all());
        }

    }

    public function show($id)
    {
        $blacklist = Blacklist::find($id);
        if ($blacklist) 
        {
            return $blacklist;
        } 
        else 
        {
            return response()->json(['error' => "Blacklist not found"], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $blacklist = Blacklist::find($id);
        if ($blacklist) 
        {
            if (auth()->user()->id == $blacklist->user_id) 
            {
                $blacklist->update($request->all());
                return $blacklist;
            } 
            else 
            {
                return response()->json(['error' => "You cant update someone else's blacklist"], 400);
            }
        } 
        else 
        {
            return response()->json(['error' => "Blacklist not found"], 404);
        }

    }

    public function destroy($id)
    {
        $blacklist = Blacklist::find($id);
        if ($blacklist) 
        {
            if (auth()->user()->id == $blacklist->user_id)
            {
                $blacklist = Blacklist::destroy($id);
                return $blacklist;
            } 
            else 
            {
                return response()->json(['error' => "You cant delete someone else's blacklist"], 400);
            }
        } 
        else 
        {
            return response()->json(['error' => "Blacklist not found"], 404);
        }

    }
}
