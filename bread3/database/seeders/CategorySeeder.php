<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = new Category();
        $category->name = 'Kinh Đô';
        $category->image = 1;
        $category->description = 'là một thương hiệu có từ lâu đời và rất là ngon';
        $category->save();

        $category = new Category();
        $category->name = 'Hữu Nghị';
        $category->image = 2;
        $category->description = 'là một thương hiệu có từ lâu đời và rất là ngon và phổ biến ở khắp Việt Nam';
        $category->save();

        $category = new Category();
        $category->name = 'Đức Long';
        $category->image = 3;
        $category->description = 'là một thương hiệu có từ lâu đời và rất là ngon và phổ biến ở Hà Nội';
        $category->save();
    }
}
