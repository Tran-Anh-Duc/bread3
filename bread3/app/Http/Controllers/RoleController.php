<?php

namespace App\Http\Controllers;

use App\Repositories\RoleRepository;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function index()
    {
        $roles = $this->roleRepository->getAll();
        return response()->json(['message'=>' index success','data'=>$roles],200);
    }

    public function store(Request $request)
    {
        $role = $this->roleRepository->create($request);
        return response()->json(['message'=>'create role success',$role],200);
    }

    public function update(Request $request,$id)
    {
        $this->roleRepository->edit($request,$id);
        return response()->json(['message'=>'update success'],200);
    }

    public function destroy($id)
    {
        $this->roleRepository->delete($id);
        return response()->json(['message'=>'destroy success'],200);
    }
}
