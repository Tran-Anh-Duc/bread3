<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function loginUser(Request $request)
    {
        $user = $this->authRepository->login($request);
        return response()->json(['message'=>"login success",'data'=>$user],200);
    }


    public function registerUser(Request $request)
    {
        $this->authRepository->register($request);
        return response()->json(['message' => 'register success','data'=>$request]);
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return response()->json(['message' => "logout success"]);
    }

    public function changePassword(Request $request)
    {
        $user = \auth()->user();
        if (Hash::check($request->old_password,$user->password)){
            $user->update([
               'password'=>bcrypt($request->new_password)
            ]);
            return  response()->json(['message'=>'success']);
        }else{
            return response()->json(['message'=>'error']);
        }

    }


}
