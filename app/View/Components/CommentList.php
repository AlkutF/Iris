<?php

// app/View/Components/CommentList.php
// app/View/Components/CommentList.php
namespace App\View\Components;

use App\Models\Post;
use Illuminate\View\Component;

class CommentList extends Component
{
    public $comments;
    public $post;
    public $hasMore;

    // Constructor para pasar los comentarios, la publicación y el estado de 'hasMore' al componente
    public function __construct($comments, Post $post, $hasMore)
    {
        $this->comments = $comments;
        $this->post = $post;
        $this->hasMore = $hasMore;
    }

    /**
     * Obtener la vista de componente.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('components.comment-list');
    }
}
