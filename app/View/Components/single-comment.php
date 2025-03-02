<?php

namespace App\View\Components;

use App\Models\Comment;
use Illuminate\View\Component;

class SingleComment extends Component
{
    public $comment;

    /**
     * Crear una nueva instancia del componente.
     *
     * @param Comment $comment
     * @return void
     */
    public function __construct(Comment $comment)
    {
        // Pasar el comentario al componente
        $this->comment = $comment;
    }

    /**
     * Obtener la vista / contenido que representa el componente.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.single-comment');
    }
}
