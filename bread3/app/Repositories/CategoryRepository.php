<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Http\Request;


class CategoryRepository extends  BaseRepository
{
    private $categoryModel;

    public function __construct(Category $category)
    {
        $this->categoryModel=$category;
    }

    public function edit(Request $request, $id)
    {
        $data = [
            Category::NAME => $request->name,
            Category::IMAGE => $request->image,
            Category::DESCRIPTION => $request->description,
        ];
        $result = $this->categoryModel->find($id)->update($data);
        return $result;
    }

    public function create(Request $request)
    {
        $data = [
            Category::NAME => $request->name,
            Category::IMAGE => $request->image,
            Category::DESCRIPTION => $request->description,
            Category::STATUS => Category::ACTIVE,
            Category::CREATED_BY => $request->created_by
        ];
        $result = $this->categoryModel->create($data);
        return $result;
    }

    public function delete($id)
    {
        $result = Category::find($id)->delete();
        return $result;
    }

    public function index()
    {
        $result = $this->categoryModel->all();
        return $result;
    }

    public function show($id)
    {
        $result = $this->categoryModel->find($id);
        return $result;
    }
}
