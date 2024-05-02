<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePassword;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{

    public function changePassword(ChangePassword $request)
    {
        $user = User::find($request->user()->id);


        // return response($request->validated()['current_password']);

        if (!Hash::check($request->validated()['current_password'], $user->password)) {
            return response()->json(['current_password' => ['La contraseña actual es incorrecta.']], 403);
        }

        $user->password = Hash::make($request->validated()['new_password']);
        $user->update();

        return response()->json(['message' => 'Contraseña actualizada correctamente.']);
    }
}
