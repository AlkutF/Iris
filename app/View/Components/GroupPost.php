<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Groups\GroupPost as GroupPostModel;
use App\Models\Groups\Group;

class GroupPost extends Component
{
    public $post;
    public $group;
   
    public function __construct(GroupPostModel $post, Group $group)
    {
        $this->post = $post;
        $this->group = $group;
    }
    
    public function render()
    {
        return view('components.group-post');
    }
}