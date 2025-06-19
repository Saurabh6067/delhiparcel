<?php

namespace App\Http\Middleware;

use App\Models\Branch;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has('bid')) {
            $deliveryPerson = Branch::find($request->session()->get('bid'));
            view()->share('deliveryPerson', $deliveryPerson);
            return $next($request);
        } else {
            return redirect('/BookingPanel')->with('error', 'Enter Email & Password');
        }
    }
}
