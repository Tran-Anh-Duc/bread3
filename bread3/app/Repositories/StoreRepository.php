<?php

namespace App\Repositories;



use App\Models\Store;
use Illuminate\Http\Request;

class StoreRepository extends BaseRepository
{
    private $storeModel;

    public function __construct(Store $store)
    {
        $this->storeModel = $store;
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


    public function index()
    {
        $result = $this->storeModel->all();
        return $result;
    }

    public function delete($id)
    {
        $result = $this->storeModel->find($id)->delete();
        return $result;
    }

    public function show($id)
    {
        $result =$this->storeModel->find($id);
        return $result;
    }
}
