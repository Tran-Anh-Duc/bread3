<?php

namespace App\Repositories;



use App\Models\Store;
use Illuminate\Http\Request;

class StoreRepository extends BaseRepository
{
    public function __construct(Store $store)
    {
        parent::__construct($store);
    }

    public function create(Request $request)
    {
        $data = $request->only("name","description","address","phone");
        $store = Store::query()->create($data);
        return $store;
    }

    public function edit(Request $request,$id)
    {
        $data = $request->only("name","description","address","phone");
        Store::query()->findOrFail($id);
        $store = Store::query()->where('id','=',$id)->update($data);
        return $store;
    }
}
