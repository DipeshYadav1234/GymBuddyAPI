<?php

namespace Database\Seeders;

use App\Models\ProductContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductContentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'cucumber', 'calories' => 6.8, 'fat' => 0.1, 'carbs' => 1.4, 'protein' => 0.4],
            ['name' => 'cabbage', 'calories' => 11.1, 'fat' => 0.1, 'carbs' => 2.4, 'protein' => 0.6],
            ['name' => 'raddish', 'calories' => 11.6, 'fat' => 0.3, 'carbs' => 2.4, 'protein' => 0.3],
            ['name' => 'celery', 'calories' => 13.5, 'fat' => 0.1, 'carbs' => 3, 'protein' => 0.6],
            ['name' => 'eggplant', 'calories' => 13.9, 'fat' => 0.1, 'carbs' => 3.3, 'protein' => 0.4],
            ['name' => 'cauliflower', 'calories' => 14.3, 'fat' => 0.3, 'carbs' => 2.5, 'protein' => 1.1]
        ];
        foreach ($data as $dat) {
            if (!(ProductContent::where('name', $dat['name'])->exists())) {
                ProductContent::create($dat);
            }
        }
    }
}
