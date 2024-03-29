<?php

namespace Label84\TagManager\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Store as Session;
use Label84\TagManager\TagManager;

class TagManagerMiddleware
{
    private TagManager $tagManager;

    private Session $session;

    public function __construct(TagManager $tagManager, Session $session)
    {
        $this->tagManager = $tagManager;
        $this->session = $session;
    }

    /** @return mixed */
    public function handle(Request $request, Closure $next)
    {
        if ($this->session->has(config('tagmanager.session_name'))) {
            $this->tagManager->set($this->session->get(config('tagmanager.session_name')));

            $this->session->pull(config('tagmanager.session_name'));
        }

        $response = $next($request);

        $this->session->flash(config('tagmanager.session_name'), $this->tagManager->get());

        return $response;
    }
}
