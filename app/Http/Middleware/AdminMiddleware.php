<?php

namespace App\Http\Middleware;

use App\Models\Prodi;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::user() && Auth::user()->hasRole([['admin', 'prodi']])) {

            // buat session untuk perguruan_tinggi_id
            $user = Auth::user();
            // buat session prodi_id
            session(['prodi_id' => $user->prodi_id]);
            session(['perguruan_tinggi_id' => $user->prodi->perguruan_tinggi_id]);
            return $next($request);
        }

        session()->flash('error', 'Maaf anda tidak punya hak akses pada Portal Admin. Silahkan login dengan Akun Admin.');
        Auth::guard('web')->logout();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
