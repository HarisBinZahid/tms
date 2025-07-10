<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Translation;

class TranslationApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create and authenticate a test user
        $this->user = User::factory()->create();
    }

    public function test_can_create_translation()
    {
        $response = $this->actingAs($this->user)->postJson('/api/translations', [
            'key' => 'welcome',
            'locale' => 'en',
            'content' => 'Welcome!',
            'tag' => 'web'
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['key' => 'welcome']);
    }

    public function test_can_update_translation()
    {
        $translation = Translation::factory()->create([
            'key' => 'greet',
            'locale' => 'en',
            'content' => 'Hi'
        ]);

        $response = $this->actingAs($this->user)->putJson("/api/translations/{$translation->id}", [
            'content' => 'Hello updated',
            'tag' => 'mobile'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['content' => 'Hello updated']);
    }

    public function test_can_search_translation()
    {
        Translation::factory()->create([
            'key' => 'hello-world',
            'locale' => 'en',
            'content' => 'Hello World',
            'tag' => 'web'
        ]);

        $response = $this->actingAs($this->user)->getJson('/api/translations/search?key=hello');

        $response->assertStatus(200)
            ->assertJsonFragment(['key' => 'hello-world']);
    }

    public function test_can_export_translations_as_json()
    {
        Translation::factory()->create([
            'key' => 'greeting',
            'locale' => 'en',
            'content' => 'Hello'
        ]);

        $response = $this->actingAs($this->user)->getJson('/api/translations/export?locale=en');

        $response->assertStatus(200)
            ->assertJsonFragment(['greeting' => 'Hello']);
    }

    public function test_can_delete_translation()
    {
        $translation = Translation::factory()->create();

        $response = $this->actingAs($this->user)->deleteJson("/api/translations/{$translation->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Translation Deleted']);

    }

    public function test_export_performance_under_500ms()
    {
        Translation::factory()->count(10000)->create(['locale' => 'en']);

        $start = microtime(true);

        $response = $this->actingAs($this->user)->getJson('/api/translations/export?locale=en');

        $duration = (microtime(true) - $start) * 1000; // ms

        $response->assertStatus(200);
        $this->assertLessThan(500, $duration, "Export took too long: {$duration}ms");
    }
}
