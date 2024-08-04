<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ShareUserData
{
    public function handle($request, Closure $next)
    {
        View::composer('*', function ($view) {
            $view->with('user', Auth::user());
        });

        return $next($request);
    }
}