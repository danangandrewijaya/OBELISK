<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChooseRoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = $request->user()->roles;
        return view('pages.auth.choose-role', compact('roles'));
    }

    public function set(Request $request)
    {
        $role = $request->input('role');
        if ($request->user()->roles->pluck('name')->contains($role)) {
            session(['active_role' => $role]);
            // dd($request->session()); // Debugging line, remove in production
            // Redirect sesuai role, bisa disesuaikan
            return redirect()->intended('/');
        }
        return redirect()->route('auth.choose-role')->withErrors('Role tidak valid.');
    }
}
