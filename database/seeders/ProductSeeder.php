<?php

namespace Database\Seeders;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->delete();

        $data = [
            [
                'name' => 'Samsung Galaxy S21',
                'slug' => 'samsung-galaxy-s21',
                'description' => 'Similique molestias exercitationem officia aut. Itaque doloribus et rerum voluptate iure. Unde veniam magni dignissimos expedita eius',
                'price' => 18500.00,
                'amount' => 100,
                'image' => 'https://via.placeholder.com/800x600.png/008876?text=samsung',
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Product::insert($data);

        // ทำการเรียกตัว ProductFactory ที่จะทำการ Faker ข้อมูลให้
        // Product::factory(999)->create();
    }
}
