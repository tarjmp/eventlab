<?php

namespace App\Http\Middleware;

use Closure;
use http\Env\Request;
use http\Header;
use Illuminate\Support\Facades\App;

class HttpsProtocol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*if (!$request->secure() && App::environment() === 'production') {
            $request->setTrustedProxies([ $request->getClientIp()], \Illuminate\Http\Request::HEADER_X_FORWARDED_ALL);
            return redirect()->secure($request->getRequestUri());
        }*/

        return $next($request);
    }
}
