<?php


namespace Modules\Bread\Http\Controllers;


use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends  BaseController
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

    public function changePassword(Request $request)
    {
        $user = $request->user();
        if (Hash::check($request->old_password,$user->password)){
            $user->update([
                'password'=>Hash::make($request->password)
            ]);
            return  response()->json(['message'=>'success']);
        }else{
            return response()->json(['message'=>'error']);
        }

    }
}
