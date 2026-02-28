<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public $title;
    public $showSearch;

    public function __construct($title = null, $showSearch = true)
    {
        $this->title = $title;
        $this->showSearch = $showSearch;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
