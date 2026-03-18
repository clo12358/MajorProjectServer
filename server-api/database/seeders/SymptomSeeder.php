<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Symptom;

class SymptomSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'name');

        $data = [
            $categories['Mood'] => [
                'Happy',
                'Sad',
                'Energetic',
                'Relaxed', 
                'Anxious',
                'Bored',
                'Tired',
                'Tense',
                'Irritable',
                'Excited',
                'Calm',
                'Depressed',
                'Frustrated',
                'Content',
                'Overwhelmed',
                'Motivated',
                'Angry',
                'Joyful',
                'Stressed',
                'Peaceful',
                'Mood Swings',
            ],
            $categories['Symptoms'] => [
                'Cramps',
                'Headache',
                'Back Pain',
                'Breast Tenderness',
                'Acne',
                'Fatigue',
                'Insomnia',
                'Dizziness',
                'Hot Flushes',
                'Chills',
                'Sweating',
                'Joint Pain',
                'Muscle Pain',
                'Swelling',
                'Itching',
                'Rash',
                'Loss of Appetite',
                'Increased Appetite',
                'Pelvic Pain',
                'Leg Cramps',
                'Night Sweats',
            ],
            $categories['Vaginal Discharge'] => [
                'Normal discharge',
                'Watery',
                'Stretchy',
                'Sticky',
                'Thick',
                'Brown',
                'Yellow',
                'Unusual odor',
                'Excessive',
            ],
            $categories['Energy Level'] => [
                'Very Low',
                'Low',
                'Normal',
                'High',
                'Very High',
            ],
            $categories['Cravings'] => [
                'Sweet',
                'Salty',
                'Savory',
                'Chocolate',
                'Carbs',
                'Fruits',
                'Vegetables',
                'Dairy',
                'Meat',
                'Spicy',
                'Sour',
                'Fatty Foods',
                'Coffee',
                'Alcohol',
                'Ice Cream',
                'Bread',
                'Citrus',
                'Nuts',
                'Pizza',
                'Burgers',
                'Sushi',
                'Tacos',
                'Ice',
                'Cheese',
                'Fast Food',
                'Candy',
            ],
            $categories['Sex & Sex Drive'] => [
                'Low Libido',
                'Neutral Libido',
                'High Libido',
                'Protected',
                'Unprotected',
                'Painful Sex',
                'Masturbation',
                'Sexual Touch',
                'Oral Sex',
                'Anal Sex',
            ],
            $categories['Gut Health'] => [
                'Constipation',
                'Diarrhea',
                'Gas',
                'Bloating',
                'Abdominal Pain',
                'Nausea',
                'Vomiting',
                'Heartburn',
                'Indigestion',
                'Appetite Changes',
            ],
            $categories['PCOS Symptoms'] => [
                'Weight gain',
                'Oily Skin',
                'Excess hair growth',
                'Thinning hair',
                'Dark skin patches',
                'Skin tags',
            ],
            $categories['Endometriosis Symptoms'] => [
                'Severe cramps',
                'Chronic pelvic pain',
                'Heavy bleeding',
                'Painfull bowel movements',
                'Painful urination',
            ],
        ];

        foreach ($data as $categoryId => $symptoms) {
            foreach ($symptoms as $symptomName) {
                Symptom::firstOrCreate([  // 👈 skips if already exists
                    'name' => $symptomName,
                    'category_id' => $categoryId,
                ]);
            }
        }
    }
}