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

    public function handle(Request $request, Closure $next): mixed
    {
        $sessionName = config('tagmanager.session_name');

        if ($this->session->has($sessionName)) {
            $this->tagManager->set(
                collect((array) $this->session->get($sessionName, []))
            );

            $this->session->pull($sessionName);
        }

        $response = $next($request);

        $this->session->flash($sessionName, $this->tagManager->get());

        return $response;
    }
}
