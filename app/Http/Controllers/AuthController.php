<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|min:8',
        ]);        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        return $this->createNewToken($token);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|between:6,24',
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|confirmed|min:8',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        $user->assignRole('Visitor');
        return response()->json([
            'message' => 'สมัครสมาชิกสำเร็จ',
            'success' => '',
            'user' => $user
        ], 201);
    }

    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'ออกจากระบบเรียบร้อย']);
    }

    public function refresh() {
        return $this->respondWithToken(auth()->refresh());
    }

    public function userProfile() {
        $user = auth()->user();
        $roles = $user->roles; // ดึง Role จาก model_has_roles
    
        return response()->json([
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    protected function createNewToken($token){
        $user = auth()->user();
        return response()->json([
            'access_token' => $token,
            "permissions"=> auth()->user()->getPermissionsViaRoles()->pluck('name'),
            'message' => 'เข้าสู่ระบบสำเร็จ',
            'success' => '',
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user,
        ]);
    }
    
}
