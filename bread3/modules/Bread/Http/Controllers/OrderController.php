<?php


namespace Modules\Bread\Http\Controllers;


use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class OrderController extends BaseController
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function cart()
    {
        return response()->json(['message'=>'success']);
    }

    public function addOrder($id)
    {
        $cart = $this->orderRepository->addToCart($id);
        return response()->json(['message'=>'add cart success','data'=>$cart],200);
    }

    public function updateOrder(Request $request)
    {
        $this->orderRepository->updateCart($request);
        return response()->json(['message'=>'update success'],200);
    }

    public function removeOrder(Request $request)
    {
        $this->orderRepository->removeCart($request);
        return response()->json(['message'=>'delete success'],200);
    }
}
