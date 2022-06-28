<?php

namespace App\Repositories;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class OrderRepository
{

//    public function addOrder($id)
//    {
//        $product = ProductController::query()->select('name', 'price', 'category_id', 'id', 'image', 'quantity')->find($id);
//        return Cart::add(['id' => $id, 'name' => $product->name, 'quantity' => 1, 'price' => $product->price,
//            'weight'=> 200,
//            'options' => ['image' => $product->image]])->back();
//    }
//
//    public function removeOrder($id)
//    {
//        $cart = Cart::remove($id);
//        $cart->delete()->back();
//    }
//
//    public function updateOrder(Request $request,$id)
//    {
//        return Cart::update($id,$request->input('update_qty'))->back();
//    }

    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->price,
                'image' => $product->image,
            ];
        }
        session()->put('cart', $cart);
        return $cart;
    }


    public function updateCart(Request $request)
    {
        if ($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]['quantity'] = $request->quantity;
            session()->put('cart',$cart);
        }
        session()->flash('success', 'Cart updated successfully');
    }



    public function removeCart(Request $request)
    {
        if ($request->id){
            $cart = session()->get('cart');
            if (isset($cart[$request->id])){
                unset($cart[$request->id]);
                session()->put('cart',$cart);
            }
            session()->flash('success', 'ProductController removed successfully');
        }
    }


}
