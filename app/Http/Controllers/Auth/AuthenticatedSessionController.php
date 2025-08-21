<?php

namespace App\Http\Controllers\Auth;

use App\Core\Constants;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Dosen;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        if ($roles->count() === 1) {
            // Jika hanya satu role, set ke session dan redirect sesuai role
            $role = $roles->first();
            session(['active_role' => $role]);
            // store prodi_id from user if available
            // Prefer prodi_id from role assignment pivot (model_has_roles) if present
            $pivot = DB::table(config('permission.table_names.model_has_roles'))
                ->where('model_type', get_class($user))
                ->where('model_id', $user->getKey())
                ->where('role_id', DB::table(config('permission.table_names.roles'))->where('name', $role)->value('id'))
                ->first();

            if ($pivot && isset($pivot->prodi_id) && $pivot->prodi_id) {
                session(['prodi_id' => $pivot->prodi_id]);
            } elseif (isset($user->prodi_id) && $user->prodi_id) {
                // fallback to user's prodi_id if pivot doesn't have it
                session(['prodi_id' => $user->prodi_id]);
            }

            if ($role === Constants::ROLE_DOSEN) {
                $nip = $user->nip;
                $dosen = Dosen::where('nip', $nip)->first();
                if ($dosen) {
                    session(['dosen_id' => $dosen->id]);
                    session(['nip' => $nip]);
                    // if dosen has a prodi relation/column, prefer that
                    if (isset($dosen->prodi_id) && $dosen->prodi_id) {
                        session(['prodi_id' => $dosen->prodi_id]);
                    }
                }
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
