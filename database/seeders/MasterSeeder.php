<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Stock;
use App\Models\Store;
use App\Models\Supplier;
use Illuminate\Support\Str;

class MasterSeeder extends Seeder
{
    public function run()
    {
        // 1. Stores
        $store = Store::first() ?: Store::create([
            'title' => 'Main Sports Hub',
            'location' => 'Block A, City Center',
            'phone' => '+123456789'
        ]);

        // 2. Categories
        $categories = [
            ['title' => 'Ball Sports', 'slug' => 'ball-sports', 'image' => 'https://images.unsplash.com/photo-1518063319789-7217e6706b04?w=100'],
            ['title' => 'Cricket', 'slug' => 'cricket', 'image' => 'https://images.unsplash.com/photo-1531415074968-036ba1b575da?w=100'],
            ['title' => 'Football', 'slug' => 'football', 'image' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=100'],
            ['title' => 'Racket Sports', 'slug' => 'racket-sports', 'image' => 'https://images.unsplash.com/photo-1622279457486-62dcc4a4977d?w=100'],
            ['title' => 'Fitness & Gym', 'slug' => 'fitness-gym', 'image' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=100'],
        ];

        foreach ($categories as $cat) {
            $c = Category::updateOrCreate(['slug' => $cat['slug']], $cat);
            
            // 3. Brands (per category)
            $brand = Brand::updateOrCreate(
                ['slug' => $c->slug . '-pro'],
                [
                    'title' => Str::studly($c->slug) . ' Pro',
                    'image' => 'https://ui-avatars.com/api/?name=Brand&background=random'
                ]
            );

            // 4. Products
            for ($i = 1; $i <= 3; $i++) {
                $product = Product::updateOrCreate(
                    ['slug' => $c->slug . '-item-' . $i],
                    [
                        'title' => $c->title . ' Item ' . $i,
                        'code' => strtoupper(substr($c->slug, 0, 3)) . '-10' . $i,
                        'description' => 'Premium ' . $c->title . ' equipment for professionals.',
                        'category_id' => $c->id,
                        'brand_id' => $brand->id,
                        'price' => rand(50, 500),
                        'have_variants' => $i == 1 ? 1 : 0,
                        'status' => 1
                    ]
                );

                if ($product->have_variants) {
                    $variants = ['Small', 'Medium', 'Large'];
                    foreach ($variants as $v) {
                        $variant = ProductVariant::updateOrCreate(
                            ['product_id' => $product->id, 'title' => $v],
                            [
                                'sku' => $product->code . '-' . strtoupper($v[0]),
                                'price' => rand(10, 50)
                            ]
                        );
                        Stock::updateOrCreate(
                            ['store_id' => $store->id, 'product_id' => $product->id, 'variant_id' => $variant->id],
                            ['quantity' => rand(10, 50)]
                        );
                    }
                } else {
                    Stock::updateOrCreate(
                        ['store_id' => $store->id, 'product_id' => $product->id, 'variant_id' => null],
                        ['quantity' => rand(20, 100)]
                    );
                }
            }
        }

        // 5. Suppliers
        Supplier::create([
            'name' => 'Sports Global Ltd',
            'email' => 'sales@sportsglobal.com',
            'phone' => '987654321',
            'address' => 'London, UK'
        ]);
    }
}
