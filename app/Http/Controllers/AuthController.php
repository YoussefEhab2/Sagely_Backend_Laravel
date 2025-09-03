<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;

class AuthController extends Controller
{
     protected UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }


     public function register(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email|unique:user,email',
            'password'    => 'required|string|min:6',
            'phoneNumber' => 'nullable|string|max:20',
        ]);

        $result = $this->service->register($request->all());

        return response()->json([
            'message' => 'User registered successfully',
            'user'    => $result['user'],
            'token'   => $result['token'],
        ], 201);
    }

    public function login(Request $request){
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        try{
            $result = $this->service->login($request->all());
            return response()->json([
                'message' => 'Login successful',
                'user'    => $result['user'],
                'token'   => $result['token'],
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'error' => 'Invalid credentials'
            ], 401);
        }
    }

    public function logout(Request $request){
        $this->service->logout();
        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }
}
