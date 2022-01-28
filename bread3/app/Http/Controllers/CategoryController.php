<?php

namespace App\Http\Controllers;

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
        $categories = $this->categoryRepository->getAll();
        return response()->json(['message' => 'index success', $categories], 200);
    }

    public function store(Request $request)
    {
        $category = $this->categoryRepository->create($request);
        return response()->json(['message' => 'create success', 'data' => $category], 200);
    }

    public function update(Request $request, $id)
    {
        $category = $this->categoryRepository->edit($request, $id);
        return response()->json(['message' => 'update success', 'data' =>$category], 200);
    }

    public function destroy($id)
    {
        $this->categoryRepository->delete($id);
        return response()->json(['message' => 'delete success'], 200);
    }
}
