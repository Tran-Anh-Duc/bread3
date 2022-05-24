<?php

namespace App\Repositories;


use App\Models\Product;
use Illuminate\Http\Request;

class ProductRepository extends BaseRepository
{
    private $productModel;

    public function __construct(Product $product)
    {
        $this->productModel = $product;
    }


    public function create(Request $request)
    {
        $data = [
            Product::NAME => $request->name,
            Product::DESCRIPTION => $request->description,
            Product::IMAGE => $request->image,
            Product::PRICE => $request->price,
            Product::CATEGORY_ID => $request->category_id,
            Product::STORE_ID => $request->store_id,
            Product::STATUS => Product::ACTIVE,
        ];
        $result = $this->productModel->create($data);
        return $result;
    }

    public function edit(Request $request, $id)
    {
        $data = [
            Product::NAME => $request->name,
            Product::DESCRIPTION => $request->description,
            Product::IMAGE => $request->image,
            Product::PRICE => $request->price,
            Product::CATEGORY_ID => $request->category_id,
            Product::STORE_ID => $request->store_id,
            Product::STATUS => Product::ACTIVE,
        ];
        $result = $this->productModel->find($id)->update($data);
        return $result;
    }

    public function index()
    {
        $result = $this->productModel->all();
        return $result;
    }

    public function delete($id)
    {
        $result = $this->productModel->find($id)->delete();
        return $result;
    }

    public function show($id)
    {
        $result =$this->productModel->find($id);
        return $result;
    }


}
