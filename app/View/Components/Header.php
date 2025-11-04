<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Header extends Component
{
    public $title;
    public $subtitle;
    public $icon;

    public function __construct($title = null, $subtitle = null, $icon = null)
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->icon = $icon;
    }

    public function render()
    {
        return view('components.header');
    }
}
