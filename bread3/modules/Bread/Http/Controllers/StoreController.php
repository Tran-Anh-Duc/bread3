<?php


namespace Modules\Bread\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Repositories\StoreRepository;
use Illuminate\Http\Request;

class StoreController extends Controller
{

    protected $storeRepository;

    public function __construct(StoreRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    public function index()
    {
        $stores = $this->storeRepository->getAll();
        return response()->json($stores);
    }


    public function store(Request $request)
    {
        $store = $this->storeRepository->create($request);
        return response()->json(['message'=>'create success', 'data'=>$store],200);
    }


    public function update(Request $request, $id)
    {
        $store = $this->storeRepository->edit($request,$id);
        return response()->json(['message'=>'update success','data'=>$store],200);
    }


    public function destroy($id)
    {
        $this->storeRepository->delete($id);
        return response()->json(['message'=>'delete success'],200);
    }

}
