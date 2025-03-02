<?php

namespace App\Models\Groups;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReactionPostGroup extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'post_id', 'reaction_type'];
}
