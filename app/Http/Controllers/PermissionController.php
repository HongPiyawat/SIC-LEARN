<?php

namespace App\Http\Controllers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return Permission::paginate(10);
    }

    public function show_all()
    {
        return Permission::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'guard_name' => 'required|string',
            'title' => 'required|string',
            'type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "error", "error" => $validator->errors()]);
        }

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
            'title' => $request->title,
            'type' => $request->type,
        ]);

        event(new Registered($permission));

        return response()->json([
            "success" => ""
        ]);
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json([
                "status" => "error",
                "error" => "Permission not found."
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'guard_name' => 'required|string',
            'title' => 'required|string',
            'type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "error", "error" => $validator->errors()]);
        }

        $permission->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
            'title' => $request->title,
            'type' => $request->type,
        ]);

        return response()->json([
            "success" => ""
        ]);
    }

    public function destroy($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json([
                "status" => "error",
                "error" => "Permission not found."
            ], 404);
        }

        $permission->delete();

        return response()->json([
            "success" => ""
        ]);
    }
}
