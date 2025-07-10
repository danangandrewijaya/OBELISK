<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Dosen;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        addJavascriptFile('assets/js/custom/authentication/sign-in/general.js');

        return view('pages/auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();
        $user->update([
            'last_login_at' => Carbon::now()->toDateTimeString(),
            'last_login_ip' => $request->getClientIp()
        ]);

        $roles = $user->roles->pluck('name');
        // dd($roles); // Debugging line, remove in production
        if ($roles->count() === 1) {
            // Jika hanya satu role, set ke session dan redirect sesuai role
            $role = $roles->first();
            session(['active_role' => $role]);
            if($role === 'dosen') {
                $nip = auth()->user()->nip;
                $user = Dosen::where('nip', $nip)->first();
                $dosen = Dosen::where('id', $user->id)->first();
                session(['dosen_id' => $dosen->id]);
                session(['nip' => $nip]);
            }
                return redirect()->intended(RouteServiceProvider::HOME);
        } elseif ($roles->count() > 1) {
            // Jika lebih dari satu role, redirect ke halaman pemilihan role
            return redirect()->route('auth.choose-role');
        } else {
            // Tidak ada role, fallback
            return redirect()->intended(RouteServiceProvider::HOME);
        }
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
