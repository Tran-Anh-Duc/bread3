<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $store = new Store();
        $store->name = 'Cửa hàng 1';
        $store->description = 'quy mô 3 tầng lầu , chủ yếu sản xuất bánh  tại chỗ';
        $store->address =  '73 Ngọc Lâm, Long biên, Hà Nội';
        $store->phone = '0123456789';
        $store->save();

        $store = new Store();
        $store->name = 'Cửa hàng 2';
        $store->description = 'Quy mô 1 mặt sàn , chuyên cung cấp bánh tiệc cưới';
        $store->address =  '46/366 Ngọc Lâm , Long Biên , Hà Nội';
        $store->phone = '0987654321';
        $store->save();

        $store = new Store();
        $store->name = 'Cửa hàng 3';
        $store->description = 'Quy mô 2 tầng là trung tâm tổ chức các sự kiện bánh mỳ ';
        $store->address =  'Nhà văn hóa phường Ngọc Lâm , Long Biên , Hà Nội ';
        $store->phone = '099999999';
        $store->save();

    }
}
