<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Mood',
            'Symptoms',
            'Vaginal Discharge',
            'Energy Level',
            'Cravings',
            'Sex & Sex Drive',
            'Gut Health',
            'PCOS Symptoms',
            'Endometriosis Symptoms',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}