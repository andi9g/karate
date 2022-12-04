<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GerbangAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $posisi = $request->session()->get('posisi');
        if ($posisi === "admin") {
            return $next($request);
        }else {
            $request->session()->flush();
            return redirect('login')->with('toast_error', 'Error');
        }
    }
}
