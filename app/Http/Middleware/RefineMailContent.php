<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class RefineMailContent
 * @package App\Http\Middleware
 */
class RefineMailContent
{
    /**
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->merge([
            'content' => [
                'value' => addslashes($request->input('content.value')),
                'type' => $request->input('content.type'),
            ],
        ]);

        return $next($request);
    }
}
