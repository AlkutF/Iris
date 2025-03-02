<?php

namespace App\Models\Groups;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Groups\CommentGroup;
use App\Models\Groups\ReactionPostGroup;

class GroupPost extends Model
{
    use HasFactory;

    protected $table = 'group_posts';

    protected $fillable = [
        'group_id', 
        'user_id', 
        'content', 
        'media_url',
        'permissions'
    ];

    // Relación con el grupo
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    // Relación con el usuario que crea la publicación
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(CommentGroup::class, 'post_id');
    }

    public function reactions()
    {
        return $this->hasMany(ReactionPostGroup::class, 'post_id');
    }
    public function getReactionCountByType($type)
    {
        return $this->reactions()->where('reaction_type', $type)->count();
    }

    
}
