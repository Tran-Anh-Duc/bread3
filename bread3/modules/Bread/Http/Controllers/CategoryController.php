<?php


namespace Modules\Bread\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryRepository;

     public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $categories = $this->categoryRepository->index();
        return response()->json([
            'message' => 'index success',
            'status' => '200',
            'data' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $category = $this->categoryRepository->create($request);
        return response()->json(['message' => 'create success', 'data' => $category], 200);
    }

    public function update(Request $request, $id)
    {
        $category = $this->categoryRepository->edit($request, $id);
        return response()->json([
            'message'=>'update success',
            'status' => '200',
            'data'=>$category
        ]);
    }

    public function destroy($id)
    {
        $this->categoryRepository->delete($id);
        return response()->json([
            'message' => 'update success',
            'status' => '200',
        ]);
    }

    public function show($id)
    {
        $result = $this->categoryRepository->show($id);
        return $result;
    }


}
