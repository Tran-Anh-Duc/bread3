<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Http\Request;


class CategoryRepository extends  BaseRepository
{
    public function __construct(Category $category)
    {
        parent::__construct($category);
    }

    public function edit(Request $request,$id)
    {
        $data = $request->only("name","description","image");
        Category::query()->findOrFail($id);
        if ($request->hasFile('image')){
            $image = $request->file('image');
            $data['image'] = time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('image');
            $image->move($path,$data['image']);
        }
        return  Category::query()->where('id','=',$id)->update($data);

//        $data = $request->only("name","description","image");
//        if ($image = $request->file()){
//            $imagePath = 'uploads/';
//            $imageCategory = time() . "." . $image->getClientOriginalExtension();
//            $image->move($imagePath,$imageCategory);
//            $data['image'] = "$imageCategory";
//        }else{
//            unset($data['image']);
//        }
//        return Category::query()->where('id', '=', $id)->update($data);

    }

    public function create(Request $request)
    {
        $data = $request->all();
        if ($request->hasFile('image')){
            $image = $request->file('image');
            $link = time() . '.' .$image->getClientOriginalExtension();
            $path = public_path('image');
            $image->move($path,$link);
        }
        return Category::query()->create($data);
    }
}
