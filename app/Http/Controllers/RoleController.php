<?php

namespace App\Http\Controllers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        return Role::paginate(10);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'guard_name' => 'required|string',
            'title' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "error", "error" => $validator->errors()]);
        }

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
            'title' => $request->title,
        ]);

        event(new Registered($role));

        return response()->json([
            "success" => ""
        ]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                "status" => "error",
                "error" => "Role not found."
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'guard_name' => 'required|string',
            'title' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "error", "error" => $validator->errors()]);
        }

        $role->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
            'title' => $request->title,
        ]);

        return response()->json([
            "success" => ""
        ]);
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                "status" => "error",
                "error" => "Role not found."
            ], 404);
        }

        $role->delete();

        return response()->json([
            "success" => ""
        ]);
    }
}
