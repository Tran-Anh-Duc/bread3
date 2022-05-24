<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Models\Product;
use App\Repositories\CategoryRepository;
use App\Repositories\StoreRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;


class ProductController extends Controller
{

    protected $productRepository;
    protected $categoryRepository;
    protected $storeRepository;


    public function __construct(ProductRepository $productRepository,
                                CategoryRepository $categoryRepository,
                                StoreRepository $storeRepository)

    {
        $this->storeRepository = $storeRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }


    // hiện tất cả các sản phẩm

    public function index()
    {
        $products = $this->productRepository->index();
        return response()->json([
            'message' => 'index success',
            'status'=> 200,
            'data' => $products
        ]);
    }


    //thêm mới một sản phẩm

    public function store(Request $request)
    {
        $product = $this->productRepository->create($request);
        $category = $this->categoryRepository->getAll();
        $store = $this->storeRepository->getAll();
        return response()->json(['message' => 'create success', 'data' => $product, $category, $store], 200);
    }


    //detail một sản phẩm

    public function show($id)
    {
        $product = $this->productRepository->show($id);
        return response()->json([
            'message' => ' show product success',
            'status'=> 200,
            'data' => $product
        ]);
    }


    // update một sản phẩm

    public function update(Request $request, $id)
    {
        $product = $this->productRepository->edit($request, $id);
        $category = $this->categoryRepository->getAll();
        $store = $this->storeRepository->getAll();
        return response()->json(['message' => 'update success', 'data' => $product], 200);

    }

    //xóa một sản phẩm

    public function destroy($id)
    {
        $this->productRepository->delete($id);
        return response()->json(['message' => 'delete success',], 200);
    }

    //search tìm kiếm một sản phẩm

    public function searchProduct($name)
    {

        $data = Product::query()->where('name','LIKE','%'.$name.'%')
            ->get();
        if (count($data)){
            return response()->json(['message' => 'search success','data'=>$data]);
        }else{
            return response()->json(['message' => 'No Data not found'], 404);
        }
    }


    //search filter bộ lọc theo tên của category

    public function searchFilter(Request $request)
    {

        $data = $request->get('name');
        $result = Category::query()->where('name','LIKE',"%{$data}")
            ->get();
        return response()->json(['message'=>'filter success','data'=>$result],200);
    }

}
