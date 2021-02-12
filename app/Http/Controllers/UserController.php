<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }
    public function Authorization(Request $request)
    {
        $email = $request->email;
        $password = User::query()->
            where('email', $email)->
            first()->password;
        $auth = auth()->attempt(['email' => $email, 'password' => $request->password]);
        if ($auth) 
        {
            return $auth;
        } else 
        {
            return response()->json(['error' => 'Bad request'], 400);
        }

    }

    public function store(Request $request)
    {
        $user = User::forceCreate([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'api_token' => Str::random(50),
        ]);
        if ($user) 
        {
            $this->UpdateUserInfoRedis($user->id);
            return $user;
        } else 
        {
            return response()->json(['error' => "Bad request"], 400);
        }

    }

    public function show($id)
    {
        $user = Redis::get('profile/' . $id);
        if ($user) {
            return $user;
        } else {
            return response()->json(['error' => "User not found"], 404);
        }

    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->update($request->all());
        $this->updateUserInfoRedis(auth()->user()->id);
        return $user;

    }

    public function news(Request $request)
    {
        $news = Post::query()
            ->leftJoin('Subscribe', 'Subscribe.subscriber_id', '=', 'posts.user_id')
            ->where('Subscribe.user_id', '=', auth()->user()->id)
            ->limit(50)
            ->get();
        return $news;

    }

    public function getPosts(Request $request)
    {
        $userId = $request->user_id;
        $posts = Post::query()
            ->where('user_id', $userId)
            ->get();
        if ($posts) 
        {
            return $posts;
        } else 
        {
            return response()->json(['error' => "Posts not found"], 404);
        }

    }

    public function getProfileInfo(Request $request, $id)
    {
        $userId = $id;
        $Subscribers = User::query()
            ->select('Subscribe.user_id as user', 'Subscribe.subscriber_id as subscriber')
            ->join('Subscribe', 'users.id', '=', 'Subscribe.user_id')
            ->where('Subscribe.user_id', $userId)
            ->orWhere('Subscribe.subscriber_id', $userId)
            ->get();
        if (auth()->user()->id == $userId) 
        {
            return $Subscribers;
        } 
        else 
        {
            $blacklist = User::query()
                ->select('Blacklist.blocked_user_id as  blocked user')
                ->join('Blacklist', 'users.id', '=', 'Blacklist.user_id')
                ->where('Blacklist.blocked_user_id', $userId)
                ->get();
            if (!is_null($blacklist)) 
            {
                return response()->json(['error' => "Cant show profile info, you are in blacklist"], 400);
            } 
            else 
            {
                return $Subscribers;
            }
        }

    }

}
