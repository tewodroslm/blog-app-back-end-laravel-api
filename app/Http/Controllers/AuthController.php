<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller{
    
    public function register(Request $request){
        $valData = $request->validate([
            'name'=>'required|string',
            'email'=>'email|required|unique:users',
            'password'=>'required|confirmed'
        ]);

        $valData['password'] = bcrypt($request->password);
    
        $user = User::create($valData);
    
        $accessToken = $user->createToken('authToken')->accessToken;
            
        return response(['user'=>$user, 'access_token'=>$accessToken]);
    }

    public function login(Request $request){
        $cred = $validatedData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);
     
        if( !auth()->attempt($cred)){
            return response(['message'=>'Invalid credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['user' => auth()->user() , 'access_token'=>$accessToken]);
    }

    public function logout(Request $request){
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
}

}
