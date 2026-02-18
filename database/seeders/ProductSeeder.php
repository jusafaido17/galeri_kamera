<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil kategori
        $dslr = Category::where('slug', 'dslr-camera')->first();
        $mirrorless = Category::where('slug', 'mirrorless-camera')->first();
        $action = Category::where('slug', 'action-camera')->first();
        $lensa = Category::where('slug', 'lensa')->first();
        $tripod = Category::where('slug', 'tripod-stabilizer')->first();
        $lighting = Category::where('slug', 'lighting')->first();

        $products = [
            // DSLR Camera
            [
                'category_id' => $dslr->id,
                'name' => 'Canon EOS 5D Mark IV',
                'description' => 'Kamera DSLR full-frame profesional dengan sensor 30.4MP, video 4K, dan performa luar biasa untuk fotografi komersial.',
                'specifications' => json_encode([
                    'Sensor' => '30.4 Megapixel Full-Frame CMOS',
                    'Video' => '4K 30fps, Full HD 60fps',
                    'ISO' => '100-32000 (expandable to 50-102400)',
                    'Autofocus' => '61-point AF system',
                    'LCD' => '3.2-inch touchscreen'
                ]),
                'stock' => 3,
                'price_6_hours' => 300000,
                'price_12_hours' => 500000,
                'price_24_hours' => 800000,
                'price_1_5_days' => 1200000,
            ],
            [
                'category_id' => $dslr->id,
                'name' => 'Nikon D850',
                'description' => 'DSLR resolusi tinggi dengan 45.7MP, cocok untuk landscape dan portrait photography profesional.',
                'specifications' => json_encode([
                    'Sensor' => '45.7 Megapixel Full-Frame CMOS',
                    'Video' => '4K UHD 30fps',
                    'ISO' => '64-25600 (expandable to 32-102400)',
                    'Autofocus' => '153-point AF system',
                    'LCD' => '3.2-inch tilting touchscreen'
                ]),
                'stock' => 2,
                'price_6_hours' => 350000,
                'price_12_hours' => 550000,
                'price_24_hours' => 900000,
                'price_1_5_days' => 1300000,
            ],

            // Mirrorless Camera
            [
                'category_id' => $mirrorless->id,
                'name' => 'Sony A7 III',
                'description' => 'Mirrorless full-frame yang ringkas dengan autofocus cepat, stabilisasi 5-axis, dan performa video 4K yang excellent.',
                'specifications' => json_encode([
                    'Sensor' => '24.2 Megapixel Full-Frame CMOS',
                    'Video' => '4K 30fps, Full HD 120fps',
                    'ISO' => '100-51200 (expandable to 50-204800)',
                    'Autofocus' => '693-point AF system',
                    'Stabilization' => '5-axis in-body'
                ]),
                'stock' => 4,
                'price_6_hours' => 280000,
                'price_12_hours' => 480000,
                'price_24_hours' => 750000,
                'price_1_5_days' => 1100000,
            ],
            [
                'category_id' => $mirrorless->id,
                'name' => 'Fujifilm X-T4',
                'description' => 'Mirrorless APS-C dengan stabilisasi in-body, film simulation mode, dan cocok untuk fotografi dan videografi hybrid.',
                'specifications' => json_encode([
                    'Sensor' => '26.1 Megapixel APS-C X-Trans CMOS 4',
                    'Video' => '4K 60fps, Full HD 240fps',
                    'ISO' => '160-12800 (expandable to 80-51200)',
                    'Autofocus' => 'Intelligent Hybrid AF',
                    'Stabilization' => '5-axis in-body up to 6.5 stops'
                ]),
                'stock' => 3,
                'price_6_hours' => 250000,
                'price_12_hours' => 450000,
                'price_24_hours' => 700000,
                'price_1_5_days' => 1000000,
            ],

            // Action Camera
            [
                'category_id' => $action->id,
                'name' => 'GoPro Hero 12 Black',
                'description' => 'Action camera terbaru dengan video 5.3K, HyperSmooth stabilization, dan waterproof hingga 10 meter.',
                'specifications' => json_encode([
                    'Video' => '5.3K 60fps, 4K 120fps',
                    'Photo' => '27 Megapixel',
                    'Stabilization' => 'HyperSmooth 6.0',
                    'Waterproof' => '10m without housing',
                    'Battery' => 'Enduro battery'
                ]),
                'stock' => 5,
                'price_6_hours' => 150000,
                'price_12_hours' => 250000,
                'price_24_hours' => 400000,
                'price_1_5_days' => 600000,
            ],
            [
                'category_id' => $action->id,
                'name' => 'DJI Osmo Action 4',
                'description' => 'Action camera dengan dual screen, sensor 1/1.3 inch, dan recording hingga 4K 120fps.',
                'specifications' => json_encode([
                    'Sensor' => '1/1.3-inch CMOS',
                    'Video' => '4K 120fps, slow motion 1080p 240fps',
                    'Display' => 'Dual touchscreen (front & back)',
                    'Stabilization' => 'RockSteady 3.0',
                    'Waterproof' => '18m'
                ]),
                'stock' => 4,
                'price_6_hours' => 140000,
                'price_12_hours' => 240000,
                'price_24_hours' => 380000,
                'price_1_5_days' => 580000,
            ],

            // Lensa
            [
                'category_id' => $lensa->id,
                'name' => 'Canon EF 24-70mm f/2.8L II USM',
                'description' => 'Lensa zoom standar profesional dengan aperture konstan f/2.8, cocok untuk berbagai jenis fotografi.',
                'specifications' => json_encode([
                    'Focal Length' => '24-70mm',
                    'Aperture' => 'f/2.8 constant',
                    'Mount' => 'Canon EF',
                    'Image Stabilization' => 'No',
                    'Weight' => '805g'
                ]),
                'stock' => 4,
                'price_6_hours' => 180000,
                'price_12_hours' => 300000,
                'price_24_hours' => 500000,
                'price_1_5_days' => 750000,
            ],
            [
                'category_id' => $lensa->id,
                'name' => 'Sony FE 70-200mm f/2.8 GM OSS',
                'description' => 'Lensa telefoto zoom premium dengan stabilisasi optik, tajam di semua aperture, ideal untuk portrait dan wildlife.',
                'specifications' => json_encode([
                    'Focal Length' => '70-200mm',
                    'Aperture' => 'f/2.8 constant',
                    'Mount' => 'Sony E',
                    'Image Stabilization' => 'Optical SteadyShot',
                    'Weight' => '1480g'
                ]),
                'stock' => 2,
                'price_6_hours' => 250000,
                'price_12_hours' => 420000,
                'price_24_hours' => 700000,
                'price_1_5_days' => 1050000,
            ],

            // Tripod & Stabilizer
            [
                'category_id' => $tripod->id,
                'name' => 'Manfrotto MT055XPRO3',
                'description' => 'Tripod aluminium profesional dengan 90° column mechanism, mendukung berat hingga 9kg.',
                'specifications' => json_encode([
                    'Material' => 'Aluminum',
                    'Max Height' => '170cm',
                    'Min Height' => '9cm',
                    'Load Capacity' => '9kg',
                    'Weight' => '2.5kg'
                ]),
                'stock' => 6,
                'price_6_hours' => 80000,
                'price_12_hours' => 130000,
                'price_24_hours' => 200000,
                'price_1_5_days' => 300000,
            ],
            [
                'category_id' => $tripod->id,
                'name' => 'DJI Ronin-SC',
                'description' => 'Gimbal stabilizer 3-axis untuk mirrorless, payload hingga 2kg, mode ActiveTrack dan Force Mobile.',
                'specifications' => json_encode([
                    'Axis' => '3-axis',
                    'Payload' => '2kg',
                    'Battery' => '11 hours runtime',
                    'Features' => 'ActiveTrack 3.0, Force Mobile',
                    'Weight' => '1.1kg'
                ]),
                'stock' => 3,
                'price_6_hours' => 200000,
                'price_12_hours' => 350000,
                'price_24_hours' => 550000,
                'price_1_5_days' => 800000,
            ],

            // Lighting
            [
                'category_id' => $lighting->id,
                'name' => 'Godox SL-60W LED Video Light',
                'description' => 'Lampu LED continuous light 60W dengan Bowens mount, color temperature 5600K, untuk video dan foto.',
                'specifications' => json_encode([
                    'Power' => '60W',
                    'Color Temperature' => '5600K ±300K',
                    'CRI' => '95+',
                    'Mount' => 'Bowens',
                    'Dimming' => '0-100%'
                ]),
                'stock' => 5,
                'price_6_hours' => 120000,
                'price_12_hours' => 200000,
                'price_24_hours' => 320000,
                'price_1_5_days' => 480000,
            ],
            [
                'category_id' => $lighting->id,
                'name' => 'Aputure 120D Mark II',
                'description' => 'LED COB light 120W dengan kontrol wireless, CRI/TLCI 96+, sangat terang dan ideal untuk produksi video profesional.',
                'specifications' => json_encode([
                    'Power' => '120W COB LED',
                    'Color Temperature' => '5500K',
                    'CRI/TLCI' => '96+/97+',
                    'Control' => 'Wireless DMX',
                    'Lux' => '69,300 lux @ 0.5m'
                ]),
                'stock' => 2,
                'price_6_hours' => 200000,
                'price_12_hours' => 350000,
                'price_24_hours' => 550000,
                'price_1_5_days' => 850000,
            ],
        ];

        foreach ($products as $product) {
            Product::create([
                'category_id' => $product['category_id'],
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'description' => $product['description'],
                'specifications' => $product['specifications'],
                'stock' => $product['stock'],
                'price_6_hours' => $product['price_6_hours'],
                'price_12_hours' => $product['price_12_hours'],
                'price_24_hours' => $product['price_24_hours'],
                'price_1_5_days' => $product['price_1_5_days'],
                'is_available' => true,
            ]);
        }
    }
}
