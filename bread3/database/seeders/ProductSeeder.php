<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = new Product();
        $product->name = 'Bánh mỳ áp chảo';
        $product->description = 'một dòng bánh mỳ mà khách hàng dưới mười tám tuổi rất là ưa chuộng';
        $product->image = '1';
        $product->price = '50000';
        $product->category_id = 1;
        $product->store_id = 1;
        $product->save();

        $product = new Product();
        $product->name = 'Bánh mỳ sốt vang';
        $product->description = 'một dòng bánh mỳ mà khách hàng dưới mười tám tuổi rất là ưa chuộng';
        $product->image = '2';
        $product->price = '60000';
        $product->category_id = 2;
        $product->store_id = 2;
        $product->save();

        $product = new Product();
        $product->name = 'Bánh mỳ nướng mật ong';
        $product->description = 'một dòng bánh mỳ mà khách hàng dưới mười tám tuổi rất là ưa chuộng';
        $product->image = '3';
        $product->price = '70000';
        $product->category_id = 3;
        $product->store_id = 3;
        $product->save();
    }
}
