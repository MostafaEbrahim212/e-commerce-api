<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogPutRequests
{
    public function handle($request, Closure $next)
    {
        if ($request->isMethod('put')) {
            Log::info('PUT Request:', ['request' => $request->all(), 'headers' => $request->headers->all()]);
        }

        return $next($request);
    }
}
