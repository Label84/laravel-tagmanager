<?php

namespace Label84\TagManager\View\Components;

use Illuminate\Session\Store as Session;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;
use Label84\TagManager\TagManager;

class Head extends Component
{
    public TagManager $tagManager;
    public Session $session;
    public string $id;
    public bool $isEnabled;

    public function __construct(TagManager $tagManager, Session $session)
    {
        $this->tagManager = $tagManager;
        $this->session = $session;

        $this->id = config('tagmanager.id');
        $this->isEnabled = config('tagmanager.enabled');
    }

    public function render(): View
    {
        $sessionData = $this->session->get(config('tagmanager.session_name')) ?? new Collection();

        $data = $sessionData->merge($this->tagManager->get());

        $this->tagManager->clear();

        return view('tagmanager::components.head')->with('data', $data);
    }
}
