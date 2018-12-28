<?php

namespace App\Http\Middleware;

use Closure;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$transformer)
    {
        $transfromedInput = [];
        foreach ($request->request->all() as $input => $value) {
            # code...
            $transfromedInput[$transformer::getOrignalAttribute($input)] = $value;
        }
        $request->replace($transfromedInput);
        return $next($request);
    }
}
