<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Http\Request;


class RoleRepository extends BaseRepository
{
    public function __construct(Role $role)
    {
        parent::__construct($role);
    }

    public function edit(Request $request, $id)
    {
        $data = $request->only("name");
        Role::query()->findOrFail($id);
        return Role::query()->where("id","=",$id)->update($data);
    }

    public function create(Request $request)
    {
        $data = $request->only("name");
        $role = Role::query()->create($data);
        return $role;
    }
}
