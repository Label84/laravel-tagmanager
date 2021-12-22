<?php

namespace Label84\TagManager\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Label84\TagManager\TagManager;

class TagManagerUserIdMiddleware
{
    private TagManager $tagManager;

    public function __construct(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
    }

    /** @return mixed */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()) {
            $this->tagManager->setUserId($request->user()->{config('tagmanager.user_id_key')});
        }

        return $next($request);
    }
}
