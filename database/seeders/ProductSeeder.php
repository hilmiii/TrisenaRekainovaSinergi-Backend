<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Lemari Asam Prosafeaire',
                'slug' => Str::slug('Lemari Asam Prosafeaire'),
                'description' => 'Lemari asam (Fume Hood) berkualitas tinggi standar ISO.',
                'short_description' => 'Fume Hood pelindung operator dari uap berbahaya.',
                'image_url' => '/src/assets/img/lemariAsam.jpg', 
                'base_price' => 25000000,
                'category' => 'fume_hood',
                'materials' => ["Multiplex 18mm", "Stainless Steel 304", "Polypropylene"],
                'sizes' => ["1200 x 800 x 2400 mm", "1500 x 800 x 2400 mm", "1800 x 800 x 2400 mm"],
                'colors' => ["Light Grey", "White", "Blue"],
                'features' => ["Chemical Resistant", "Explosion Proof Lamp", "Digital Controller"]
            ],
            [
                'name' => 'Laminar Air Flow',
                'slug' => Str::slug('Laminar Air Flow'),
                'description' => 'Meja kerja steril untuk inokulasi mikrobiologi.',
                'short_description' => 'Clean bench sterile work area.',
                'image_url' => '/src/assets/img/laminarAirFlow.jpg',
                'base_price' => 18500000,
                'category' => 'laminar_flow',
                'materials' => ["Steel Powder Coating", "Stainless Steel 304"],
                'sizes' => ["1200 mm", "1500 mm"],
                'colors' => ["White"],
                'features' => ["HEPA Filter H14", "UV Lamp", "Air Velocity Display"]
            ],
            [
                'name' => 'Fume Hood Scrubber Prosafeaire',
                'slug' => Str::slug('Fume Hood Scrubber Prosafeaire'),
                'description' => 'Sistem penyaring udara buangan lemari asam untuk menjaga kualitas udara lingkungan.',
                'short_description' => 'Air Purification System.',
                'image_url' => '/src/assets/img/fumeHood.jpg',
                'base_price' => 20500000,
                'category' => 'fume_hood',
                'materials' => ["Steel Powder Coating", "Stainless Steel 304"],
                'sizes' => ["1200 mm", "1500 mm"],
                'colors' => ["White"],
                'features' => ["HEPA Filter H14", "UV Lamp", "Air Velocity Display"]
            ],
        ];

        foreach ($products as $product) {
            // Menggunakan updateOrCreate agar jika dijalankan 2x tidak terjadi error duplikat
            Product::updateOrCreate(['slug' => $product['slug']], $product);
        }
    }
}