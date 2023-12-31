<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    function register(Request $request)
    {
        //validate data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false, 'message' => $validator->errors()
            ], 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('card_reader_token')->plainTextToken;
        $success['name'] = $user->name;

        return response()->json(['success' => true, 'data' => $success, 'message' => 'User Registered successfully.'], 200);
    }

    // login api
    public function login(Request $request)
    {
        // dd($request);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $success['token'] = $user->createToken('card_reader_token')->plainTextToken;
            $success['name'] = $user->name;
           // dd($success['token']);

            return response()->json([
                'success' => true,
                'data' => $success,
                'message' => 'User is logged in successfully'
            ], 200);
        }
        else {
            return response()->json(['success' => false, 'message' => 'User is unauthorized']);
        }
    }

    // logout api
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'User Logged out']);
    }
}
