<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
         Product::create(['name' => 'Product A', 'product_id' => 'P001', 'available_stocks' => 100, 'price_per_unit' => 50, 'tax_percentage' => 10]);
        Product::create(['name' => 'Product B', 'product_id' => 'P002', 'available_stocks' => 200, 'price_per_unit' => 30, 'tax_percentage' => 5]);
        Product::create(['name' => 'Product C', 'product_id' => 'P003', 'available_stocks' => 150, 'price_per_unit' => 20, 'tax_percentage' => 18]);
        Product::create(['name' => 'Product D', 'product_id' => 'P004', 'available_stocks' => 250, 'price_per_unit' => 40, 'tax_percentage' => 8]);
        Product::create(['name' => 'Product E', 'product_id' => 'P005', 'available_stocks' => 300, 'price_per_unit' => 25, 'tax_percentage' => 15]);
        Product::create(['name' => 'Product F', 'product_id' => 'P006', 'available_stocks' => 120, 'price_per_unit' => 35, 'tax_percentage' => 12]);
        Product::create(['name' => 'Product G', 'product_id' => 'P007', 'available_stocks' => 180, 'price_per_unit' => 55, 'tax_percentage' => 20]);
        Product::create(['name' => 'Product H', 'product_id' => 'P008', 'available_stocks' => 400, 'price_per_unit' => 15, 'tax_percentage' => 5]);
        Product::create(['name' => 'Product I', 'product_id' => 'P009', 'available_stocks' => 500, 'price_per_unit' => 10, 'tax_percentage' => 3]);
        Product::create(['name' => 'Product J', 'product_id' => 'P010', 'available_stocks' => 80, 'price_per_unit' => 60, 'tax_percentage' => 25]);
    }
}
