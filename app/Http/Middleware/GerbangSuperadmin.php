<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GerbangSuperadmin
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
        if ($posisi === "superadmin") {
            return $next($request);
        }else {
            $request->session()->flush();
            return redirect('login')->with('toast_error', 'Error');
        }
    }
}
