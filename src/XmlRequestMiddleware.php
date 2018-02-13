<?php

namespace XmlMiddleware;

use Closure;

/**
 * Class XmlRequestMiddleware
 * @package XmlMiddleware
 */
class XmlRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // We pass an array to merge if the xml parsing somewhat failed, no error will trigger
        // If the xml request contains an empty body
        $request->merge($request->xml() ?: []);

        return $next($request);
    }
}