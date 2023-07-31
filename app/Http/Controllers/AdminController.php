<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function register(Request $request) {
        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if($admin) {
            return response()->json([
                'status'=> 200,
                'message'=> 'User created successfully',
                'data'=>$admin
            ]);
        } else {
            return response()->json(['status' => false]);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $token = Auth::guard('admin-api')->attempt($credentials);
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'user' => Auth::guard('admin-api')->user(),
            'authorisation' => [
                'token' => $token,
                'type' => 'Bearer'
            ]
        ]);
    }

    public function getUserInfo(){
        $user = Auth::guard('admin-api')->user();
        return response()->json(['result' => $user]);
    }

    public function logout()
    {
        Auth::guard('admin-api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}

