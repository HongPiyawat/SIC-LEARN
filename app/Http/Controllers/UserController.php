<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return response()->json($users);
    }

    public function updateRoles(Request $request, $userId)
    {
        \Log::info($request->all());

        $user = User::find($userId);
        $user->syncRoles($request->input('roles'));
    
        return response()->json([
            'message' => 'Roles updated successfully',
        ]);
    }
}
