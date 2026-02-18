<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'DSLR Camera',
                'description' => 'Kamera Digital Single-Lens Reflex untuk fotografi profesional'
            ],
            [
                'name' => 'Mirrorless Camera',
                'description' => 'Kamera tanpa cermin dengan kualitas setara DSLR namun lebih ringkas'
            ],
            [
                'name' => 'Action Camera',
                'description' => 'Kamera aksi untuk aktivitas ekstrem dan olahraga'
            ],
            [
                'name' => 'Camcorder',
                'description' => 'Kamera video untuk merekam momen penting'
            ],
            [
                'name' => 'Lensa',
                'description' => 'Berbagai jenis lensa untuk kamera DSLR dan Mirrorless'
            ],
            [
                'name' => 'Tripod & Stabilizer',
                'description' => 'Alat penstabil kamera untuk hasil foto/video yang maksimal'
            ],
            [
                'name' => 'Lighting',
                'description' => 'Peralatan pencahayaan profesional'
            ],
            [
                'name' => 'Audio Equipment',
                'description' => 'Microphone dan peralatan audio untuk video'
            ],
            [
                'name' => 'Drone',
                'description' => 'Drone dengan kamera untuk aerial photography'
            ],
            [
                'name' => 'Aksesoris',
                'description' => 'Aksesoris pendukung fotografi dan videografi'
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description']
            ]);
        }
    }
}
