<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){
        $user = User::create($request->validated());

        return response()->json($user, Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request){
        if(!Auth::attempt($request->validated())){
            return response()->json([], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::findOrFail($request->user()->id);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], Response::HTTP_ACCEPTED);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->json([], Response::HTTP_OK);
    }
}