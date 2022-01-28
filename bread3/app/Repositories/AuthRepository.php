<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{


    public function login(Request $request)
    {
        $data = $request->only("email","password","role_id");
        return Auth::attempt($data);
    }

    public function register(Request $request)
    {
        $data = $request->only("name","email","password","role_id");
        $data["password"] = Hash::make($request->password);
        return User::query()->create($data);
    }
}
