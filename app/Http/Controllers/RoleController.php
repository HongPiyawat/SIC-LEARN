<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Models\Permission;
use DB;

class RoleController extends Controller
{
    public function index()
    {
        return Role::with('permissions')->paginate(10);
    }

    public function getSelectedPermissionsDetails(Request $request)
    {
        $permissionIds = $request->input('permissionIds', []);
        
        $permissions = Permission::whereIn('id', $permissionIds)->get();

        return response()->json($permissions);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'title' => 'required|string',
            'permissions' => 'required|array',
        ]);
    
        if ($validator->fails()) {
            return response()->json(["status" => "error", "error" => $validator->errors()]);
        }
    
        $role = Role::create([
            'name' => $request->name,
            'title' => $request->title,
        ]);
    
        // Attach permissions to the role
        $role->permissions()->attach($request->permissions);
    
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
            'title' => 'required|string',
            'permissions' => 'required|array',
        ]);
    
        if ($validator->fails()) {
            return response()->json(["status" => "error", "error" => $validator->errors()]);
        }

        $role->permissions()->detach();
    
        $role->update([
            'name' => $request->name,
            'title' => $request->title,
        ]);
    
        $role->permissions()->sync($request->permissions);
    
        return response()->json(["success" => "Role updated successfully"]);
    }
    

    public function destroy($id)
    {
        DB::table("roles")->where('id', $id)->delete();
        return response()->noContent();
    }
}
