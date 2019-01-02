<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

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
        $response = $next($request);
        if(isset($response->exception) && $response->exception instanceof ValidationException) {
           $data = $response->getData();
           $transformedErrorData = array();
           foreach ($data->error as $key => $value) {
                $transformedKey = $transformer::getTransformedAttribute($key);
                $transformedErrorData[$transformedKey] = str_replace($key, $transformedKey, $value);

            }
            $data->error = $transformedErrorData;
            $response->setData($data);

        }
        return $response;
    }
}
