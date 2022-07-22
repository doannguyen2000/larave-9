<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next)
    {
        if(isset(auth()->user()->role) && auth()->user()->role == "admin"){
            return $next($request);
        }

        return response()->json(["message"=>"Insufficient permissions to take action"], 403);
    }
}
