<?php

namespace PacificDev\BlogAi\Http\Middleware\Blog;

use Closure;
use Illuminate\Http\Request;

class SuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (auth()->user()->id !== 1) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
