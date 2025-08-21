<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Prodi;
use App\Models\User;

class ChooseRoleController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Fetch assignments from model_has_roles with prodi info
        $modelHasRoles = config('permission.table_names.model_has_roles');
        $rolesTable = config('permission.table_names.roles');
        $prodiTable = (new Prodi)->getTable();

        $assignments = DB::table($modelHasRoles)
            ->where('model_type', get_class($user))
            ->where('model_id', $user->getKey())
            ->join($rolesTable, $rolesTable . '.id', '=', $modelHasRoles . '.role_id')
            ->leftJoin($prodiTable, $prodiTable . '.id', '=', $modelHasRoles . '.prodi_id')
            ->select($rolesTable . '.name as role_name', $modelHasRoles . '.prodi_id as prodi_id', $prodiTable . '.nama as prodi_name')
            ->get();

        // If there are no pivot-specific assignments, fall back to user's roles
        if ($assignments->isEmpty()) {
            $roles = $user->roles;
            return view('pages.auth.choose-role', compact('roles'));
        }

        return view('pages.auth.choose-role', compact('assignments'));
    }

    public function set(Request $request)
    {
        $role = $request->input('role');
        $prodiId = $request->input('prodi_id');

        // Validate user actually has this role assignment (with the prodi if provided)
        $roleId = DB::table(config('permission.table_names.roles'))->where('name', $role)->value('id');
        if (! $roleId) {
            return redirect()->route('auth.choose-role')->withErrors('Role tidak valid.');
        }

        $pivotQuery = DB::table(config('permission.table_names.model_has_roles'))
            ->where('model_type', get_class($request->user()))
            ->where('model_id', $request->user()->getKey())
            ->where('role_id', $roleId);

        if ($prodiId) {
            $pivotQuery->where('prodi_id', $prodiId);
        }

        $pivot = $pivotQuery->first();

        if ($pivot || $request->user()->roles->pluck('name')->contains($role)) {
            session(['active_role' => $role]);
            if ($pivot && isset($pivot->prodi_id) && $pivot->prodi_id) {
                session(['prodi_id' => $pivot->prodi_id]);
            } else {
                $fallback = $request->user()->prodi_id ?? null;
                if ($fallback) session(['prodi_id' => $fallback]);
            }

            return redirect()->intended('/');
        }

        return redirect()->route('auth.choose-role')->withErrors('Role tidak valid.');
    }
}
