<?php

namespace App\Repositories;



use App\Models\Log_products;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class ProductRepository extends BaseRepository
{
    private $productModel;
    private $log_productsModel;
    private $storeModel;

    public function __construct(Product $product,Log_products $log_products,Store $store)
    {
        $this->productModel = $product;
        $this->log_productsModel = $log_products;
        $this->storeModel = $store;
    }

    const ID = 'id';
    const CREATED_AT = 'created_at';
    const CREATED_BY = 'created_by';
    const UPDATED_AT = 'updated_at';
    const UPDATED_BY = 'updated_by';


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
            Product::PRODUCT_CODE => 'BM'.rand(10,100),
            Product::VIEW => Product::VIEW_NUMBER,
        ];
        $result = $this->productModel->where($this->storeModel::STATUS, 'active' )->create($data);
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
        $count = 0;
        $result = $this->productModel->find($id);
        if (!empty($result)) {
            $this->log_productsModel->create([
                'name' => $result['name'],
                'description' => $result['description'],
                'price' => $result['price'],
                'view' => $result['view'],
                'product_code' => $result['product_code']
            ]);
            $count++;
        };
        return $result;
    }

    public function five_products_new()
    {
        $data = [];
        $result = $this->productModel->orderBy(Product::CREATED_AT,'DESC')->limit(5)->get();
        return $result;
    }

    public function get_top_view()
    {
        $data = [];
        $result = $this->log_productsModel->orderBy($this->log_productsModel::VIEW , 'DESC')->limit(3)->get();
        return $result;
    }







}
