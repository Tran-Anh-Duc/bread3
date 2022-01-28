<?php

namespace App\Http\Controllers;


use App\Repositories\CategoryRepository;
use App\Repositories\StoreRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;



class ProductController extends Controller
{

    protected $productRepository;
    protected $categoryRepository;
    protected $storeRepository;

    public function __construct(ProductRepository $productRepository,CategoryRepository $categoryRepository , StoreRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $products = $this->productRepository->getAll();
        return response()->json(['message'=>'index success','data'=>$products],200);
    }


    public function store(Request $request)
    {
        $product = $this->productRepository->create($request);
        $category = $this->categoryRepository->getAll();
        $store = $this->storeRepository->getAll();
        return response()->json(['message'=>'create success','data'=>$product,$category,$store],200);
    }


    public function show($id)
    {
       $product = $this->productRepository->getById($id);
       return response()->json(['message'=>' show product success','data'=>$product],200);
    }


    public function update(Request $request,$id)
    {
        $product = $this->productRepository->edit($request,$id);
        $category = $this->categoryRepository->getAll();
        $store = $this->storeRepository->getAll();
        return response()->json(['message'=>'update success','data'=>$product],200);

    }

    public function destroy($id)
    {
        $this->productRepository->delete($id);
        return response()->json(['message'=>'delete success',],200);
    }
}
