<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return User::with(['roles:title'])->paginate(10);
    }
}
