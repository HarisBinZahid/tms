<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

class TranslationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        foreach (range(1, 100) as $i) {
            Translation::factory()->count(1000)->create();
        }
    }
}


