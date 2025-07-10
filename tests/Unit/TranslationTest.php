<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TranslationTest extends TestCase
{
    use RefreshDatabase;

    public function test_translation_has_fillable_fields()
    {
        $translation = Translation::create([
            'key' => 'test',
            'locale' => 'en',
            'content' => 'Test content',
            'tag' => 'web'
        ]);

        $this->assertDatabaseHas('translations', [
            'key' => 'test',
            'locale' => 'en',
            'content' => 'Test content',
            'tag' => 'web'
        ]);
    }
}
