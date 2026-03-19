<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Очистим таблицу перед заполнением (опционально)
        // Project::truncate();

        Page::create([
            'name' => 'Главная',
            'slug' => '/',
            'type' => 'index',
        ]);

        Page::create([
            'name' => 'Макияж',
            'slug' => 'makeup',
            'type' => 'dop',
        ]);

        Page::create([
            'name' => 'Макияж обучение',
            'slug' => 'makeup-learn',
            'type' => 'dop',
        ]);

        Page::create([
            'name' => 'Семейные фотосессии',
            'slug' => 'family-shooting',
            'type' => 'style',
        ]);

        Page::create([
            'name' => 'Персональная съёмка',
            'slug' => 'personal-shooting',
            'type' => 'style',
        ]);

        Page::create([
            'name' => 'Фотосессия для беременных',
            'slug' => 'pregnant-shooting',
            'type' => 'style',
        ]);

        Page::create([
            'name' => 'Парные фотосессии',
            'slug' => 'pair-shooting',
            'type' => 'style',
        ]);

        Page::create([
            'name' => 'Контент-съёмка',
            'slug' => 'content-shooting',
            'type' => 'style',
        ]);

        Page::create([
            'name' => 'Фотосессия на день рождения',
            'slug' => 'hb-shooting',
            'type' => 'style',
        ]);

        Page::create([
            'name' => 'Фотосессия Ню',
            'slug' => 'nu-shooting',
            'type' => 'style',
        ]);

        Page::create([
            'name' => 'Street съёмка',
            'slug' => 'street-shooting',
            'type' => 'style',
        ]);
    }
}