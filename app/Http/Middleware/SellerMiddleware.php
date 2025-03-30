<?php

namespace App\Http\Middleware;

use App\Models\Branch;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has('sid')) {
            $deliveryPerson = Branch::find($request->session()->get('sid'));
            view()->share('deliveryPerson', $deliveryPerson);
            return $next($request);
        } else {
            return redirect('/SellerPanel')->with('error', 'Enter Email & Password');
        }
    }
}
