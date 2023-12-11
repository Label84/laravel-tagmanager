<?php

namespace Label84\TagManager\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class MeasurementProtocolClientId extends Component
{
    public bool $isEnabled;

    public function __construct()
    {
        $this->isEnabled = config('tagmanager.enabled');
    }

    public function render(): View
    {
        return view('tagmanager::components.measurement-protocol-client-id');
    }
}
