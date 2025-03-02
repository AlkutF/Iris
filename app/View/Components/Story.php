<?php
namespace App\View\Components;

use Illuminate\View\Component;

class Story extends Component
{
    public $story;

    public function __construct($story)
    {
        $this->story = $story;
    }

    public function render()
    {
        return view('components.story');
    }
}