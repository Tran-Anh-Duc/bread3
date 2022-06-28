<?php

namespace Modules\ViewCpanel\Http\Controllers;


use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\StoreRepository;
use Modules\ViewCpanel\Http\Controllers\BaseController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class TemplateController extends BaseController
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

    public function list()
    {
        $products = $this->productRepository->index();
        $data = [];
        return view('viewcpanel::master',compact('products'));
    }


}
