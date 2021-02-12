<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public function comments()
    {
        return $this->hasMany('App\Models\Comments', 'post_id');
    }

    protected $fillable = [
        'user_id',
        'theme',
        'text',
        'pictures',
        'created_at',
    ];
}
