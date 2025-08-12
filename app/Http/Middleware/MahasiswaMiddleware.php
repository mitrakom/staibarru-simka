<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MahasiswaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user() && Auth::user()->hasRole(['mahasiswa'])) {
            return $next($request);
        }

        session()->flash('error', 'Maaf anda tidak punya hak akses pada Portal Mahasiswa. Silahkan login dengan Akun Mahasiswa.');
        Auth::guard('web')->logout();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
