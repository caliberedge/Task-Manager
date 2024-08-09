<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
        public function register(Request $request){
            try {
                $request->validate([
                    'email' => 'required|string|email|max:255',
                    'password' => 'required|string',
                    'name' => 'required|string',
                    'address' => 'required|string'
                ]);
            $user = User::create($request->all());
            return response()->json(['status' => true, 'message' => 'User Successfully Registered', 'user' => $user], 201);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $errorMessage = '';
            foreach ($errors as $fieldErrors) {
                $errorMessage = $fieldErrors[0];
                break;
            }
            return response()->json(['status' => false, 'error' => 'Validation error', 'message' => $errorMessage], 422);
        }
        }

        public function login(Request $request)
        {
            try {
            $credentials = $request->validate([
                'email' => 'required|string|email|max:255',
                'password' => 'required|string',
            ]);
    
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken("API_token")->plainTextToken;
                return response()->json([
                    'status' => true,
                    'user' => $user,
                    'token' => $token
                ], 200);
            }
                return response()->json(['status' => false, 'message' => 'Incorrect UserName or Password'], 400);
            } catch (ValidationException $e) {
                $errors = $e->errors();
                $errorMessage = '';
                foreach ($errors as $fieldErrors) {
                    $errorMessage = $fieldErrors[0];
                    break;
                }
                return response()->json(['status' => false, 'error' => 'Validation error', 'message' => $errorMessage], 422);
            }
        }
    
        public function logout(Request $request)
        {
            $user = Auth::user();
            if ($user) {
                $user->tokens()->delete();
            }
            return response()->json(['status' => true, 'message' => 'User logged out successfully']);
        }
        

}
