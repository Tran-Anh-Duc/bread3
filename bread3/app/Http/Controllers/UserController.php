<?php

namespace App\Http\Controllers;

use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userRepository;
    protected $roleRepository;

    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    public function index()
    {
        $users = $this->userRepository->getAll();
        return response()->json($users);
    }

    public function edit(Request $request, $id)
    {

        $this->userRepository->edit($request, $id);
        $this->roleRepository->getAll();
        return response()->json(['message' => 'update success', 'data'=> $request->all(), 'user'=>$id
        ],200);
    }

    public function delete($id)
    {
        $this->userRepository->delete($id);
        return response()->json(['message' => 'delete success']);
    }
}
