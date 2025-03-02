<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profile;
use App\Models\Post;
use App\Models\Group;

class Interest extends Model
{
    use HasFactory;

    public function profiles()
    {
        return $this->belongsToMany(Profile::class, 'interest_profile');
    }
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'interest_post');
    }
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'interest_group');
    }
}
