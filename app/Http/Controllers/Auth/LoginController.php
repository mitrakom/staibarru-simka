<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __construct() {}

    public function showLoginForm()
    {
        // dd('Halaman login ini sudah tidak digunakan lagi. Silahkan gunakan halaman login yang baru.');
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return $this->authenticated($request, Auth::user());
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    protected function authenticated(Request $request, $user)
    {

        if ($user->hasRole('mahasiswa')) {
            return redirect('/mahasiswa');
        } else if ($user->hasRole(['admin', 'prodi'])) {
            return redirect('/admin');
        } else if ($user->hasRole(['dosen'])) {
            return redirect('/dosen');
        }

        Auth::logout();
        return redirect()->route('login')
            ->withErrors(['email' => 'Akun Anda tidak memiliki hak akses yang dikenali.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
