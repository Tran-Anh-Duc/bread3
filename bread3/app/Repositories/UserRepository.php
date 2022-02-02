<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserRepository extends BaseRepository
{

    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function edit(Request $request,$id)
    {
        $data = $request->only("name","email","role_id");
        User::query()->findOrFail($id);
        return User::query()->where("id","=",$id)->update($data);
    }



}
