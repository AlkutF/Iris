<?php

namespace App\Models\Groups;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CommentGroup extends Model
{
    protected $fillable = ['post_id', 'user_id', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(GroupPost::class);
    }
}
