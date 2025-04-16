<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Disarankan pakai folder 'auth'
    }

    /**
     * Proses login user berdasarkan role.
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Redirect user berdasarkan role.
     */
    protected function redirectBasedOnRole($role)
    {
        return match ($role) {
            'admin'           => redirect()->intended('/dashboard'),
            'karyawan'        => redirect()->intended('/dashboard'),
            'tim_penilai'     => redirect()->intended('/dashboard'),
            'kepala_sekolah'  => redirect()->intended('/dashboard'),
            default           => $this->logoutWithError()
        };
    }

    /**
     * Logout user jika role tidak dikenali.
     */
    protected function logoutWithError()
    {
        Auth::logout();
        return redirect()->route('login')->with('error', 'Role tidak dikenali.');
    }
}
