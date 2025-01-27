<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; // Burayı eklemeyi unutma

class AuthController extends Controller
{
    public function register(Request $request){
        try {
            $validatorUser = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            if($validatorUser->fails()){
                return response()->json($validatorUser->errors());
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user'
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User created successfully',
                'user' => $user,
                'token' => $user->createToken('auth-token')->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function login(Request $request){
        try {
            $validatorUser = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ]);

            if($validatorUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation errors',
                    'errors' => $validatorUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only('email', 'password'))){
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid login details'
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            // if ($user->role !== 'admin' && $user->role !== 'editor') {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'You are not authorized to login with this role.'
            //     ], 403);
            // }

            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function profile(Request $request){
        try {
            $userData = auth()->user();
            return response()->json([
                'status' => true,
                'data' => $userData
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully'
        ]);
    }

    public function users(){
        $user = auth()->user();
        if ($user->role !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to perform this action.'
            ], 403);
        }

        $users = User::all();
        return response()->json([
            'status' => true,
            'users' => $users
        ]);
    }

    public function update(Request $request, $id){
        $authUser = Auth::user();
        if ($authUser->role !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to update this user.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'role' => ['required', 'string', Rule::in(['admin', 'user', 'pending_user'])],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 401);
        }

        $user = User::find($id);

        // Kullanıcı bulunamazsa
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully',
            'user' => $user
        ], 200);
    }
}
