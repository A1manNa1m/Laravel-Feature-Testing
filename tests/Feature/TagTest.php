<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    public function test_tag_list_requires_authentication()
    {
        $response = $this->getJson('/api/v1/tag');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_fetch_tag()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['read']); //token

        $response = $this->getJson('/api/v1/tag');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_tag()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['create']); //token

        $data = [
            'name' => 'Random tag',
        ];

        $response = $this->postJson('/api/v1/tag', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tags', [
            'name' => 'Random tag',
        ]);
    }

    public function test_authenticated_user_can_update_tag_with_put()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['update']);

        $tag = Tag::factory()->create([
            'name' => 'Codingtag',
        ]);

        $data = [
            'name' => 'Drawingtag',
        ];

        $response = $this->putJson("/api/v1/tag/{$tag->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('tags', [
            'name' => 'Drawingtag',
        ]);
    }

    public function test_authenticated_user_can_update_tag_with_patch()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['update']);

        $tag = Tag::factory()->create([
            'name' => 'Skilltag',
        ]);

        $data = [
            'name' => 'Cookingtag',
        ];

        $response = $this->patchJson("/api/v1/tag/{$tag->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('tags', [
            'name' => 'Cookingtag',
        ]);
    }

    public function test_authenticated_user_can_delete_tag()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['delete']);

        $tag = Tag::factory()->create();

        $response = $this->deleteJson("/api/v1/tag/{$tag->id}");

        $response->assertStatus(204); // No Content

        // $response->assertStatus(200);
        // $response->assertJson(['message' => 'Deleted']);

        $this->assertDatabaseMissing('tags', [
            'id' => $tag->id,
        ]);
    }
}
