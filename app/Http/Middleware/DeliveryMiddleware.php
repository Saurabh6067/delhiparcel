<?php

namespace App\Http\Middleware;

use App\Models\Branch;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeliveryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has('dyid')) {
            $deliveryPerson = Branch::find($request->session()->get('dyid'));
            view()->share('deliveryPerson', $deliveryPerson);
            return $next($request);
        } else {
            return redirect('/DeliveryPanel')->with('error', 'Enter Email & Password');
        }
    }
}
