<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscribe;

class SubscribeController extends Controller
{
    public function index()
    {
        return Subscribe::all();
    }

    public function store(Request $request)
    {
        $subscriberId = $request->subscriber_id;
        $request['user_id'] = auth()->user()->id;
        if (auth()->user()->id == $subscriberId) 
        {
            return response()->json(['error' => "You can't subscribe to yourself"], 400);
        } 
        else 
        {
            return Subscribe::create($request->all());
        }

    }

    public function show($id)
    {
        $subscribe = Subscribe::find($id);
        if ($subscribe) 
        {
            return $subscribe;
        } 
        else 
        {
            return response()->json(['error' => "Subscribe not found"], 404);
        }

    }

    public function update(Request $request, $id)
    {
        $subscriber = Subscribe::find($id);
        if ($subscriber) 
        {
            if (auth()->user()->id == $subscriber->user_id) 
            {
                $subscriber->update($request->all());
                return $subscriber;
            } 
            else 
            {
                return response()->json(['error' => "You cant update someone else's subscribe"], 400);
            }
        } 
        else 
        {
            return response()->json(['error' => "Subscriber not found"], 404);
        }

    }

    public function destroy($id)
    {
        $subscriber = Subscribe::find($id);
        if ($subscriber) 
        {
            if (auth()->user()->id == $subscriber->user_id) 
            {
                $subscriber = Subscribe::destroy($id);
                return $subscriber;
            } 
            else 
            {
                return response()->json(['error' => "You cant delete someone else's subscribe"], 400);
            }
        } 
        else 
        {
            return response()->json(['error' => "Subscriber not found"], 404);
        }

    }
}
