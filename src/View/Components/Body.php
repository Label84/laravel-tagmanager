<?php

namespace Label84\TagManager\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Body extends Component
{
    public string $id;
    public bool $isEnabled;

    public function __construct()
    {
        $this->id = config('tagmanager.id');
        $this->isEnabled = config('tagmanager.enabled');
    }

    public function render(): View
    {
        return view('tagmanager::components.body');
    }
}
