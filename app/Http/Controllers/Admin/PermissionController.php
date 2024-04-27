<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function getUserPermissions(Request $request)
    {
        $user = $request->user();

        $user->load('permissions', 'roles.permissions');

        return response()->json([
            'user' => $user->only('id', 'name', 'email'),
            'roles' => $user->roles->pluck('name'),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }
}
