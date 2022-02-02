<?php

namespace App\Repositories;


use App\Models\Product;
use Illuminate\Http\Request;

class ProductRepository extends BaseRepository
{

    public function __construct(Product $product)
    {
        parent::__construct($product);
    }

    public function create(Request $request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $link = time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('image');
            $image->move($path, $link);
        }
        return Product::query()->create($data);
    }

    public function edit(Request $request, $id)
    {
        $data = $request->all();
        Product::query()->find($id);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data['image'] = time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('image');
            $image->move($path, $data['image']);
        }
        return Product::query()->where('id', '=', $id)->update($data);
    }




}
