<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_list_requires_authentication()
    {
        $response = $this->getJson('/api/v1/profile');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_fetch_profile()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['read']); //token

        $response = $this->getJson('/api/v1/profile');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_profile()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['create']); //token

        $data = [
            'user_id' => $user->id,
            'bio' => 'Random bio here',
            'profile_image' => 'https://via.placeholder.com/200x200.png/0000cc?text=people+illo',
        ];

        $response = $this->postJson('/api/v1/profile', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'bio' => 'Random bio here',
            'profile_image' => 'https://via.placeholder.com/200x200.png/0000cc?text=people+illo',
        ]);
    }

    public function test_authenticated_user_can_update_profile_with_put()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['update']);

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'bio' => 'Random bio here',
            'profile_image' => 'https://via.placeholder.com/200x200.png/0000cc?text=people+illo',
        ]);

        $data = [
            'user_id' => $user->id,
            'bio' => 'Random Random Random',
            'profile_image' => 'https://via.placeholder.com/200x200.png/0000cc?text=people+illo',
        ];

        $response = $this->putJson("/api/v1/profile/{$profile->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'bio' => 'Random Random Random',
            'profile_image' => 'https://via.placeholder.com/200x200.png/0000cc?text=people+illo',
        ]);
    }

    public function test_authenticated_user_can_update_profile_with_patch()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['update']);

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'bio' => 'Random bio here',
            'profile_image' => 'https://via.placeholder.com/200x200.png/0000cc?text=people+illo',
        ]);

        $data = [
            'bio' =>'YikesYikes Yikes Yikes',
        ];

        $response = $this->patchJson("/api/v1/profile/{$profile->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'bio' =>'YikesYikes Yikes Yikes',
            'profile_image' => 'https://via.placeholder.com/200x200.png/0000cc?text=people+illo',
        ]);
    }

    public function test_authenticated_user_can_delete_profile()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['delete']);

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->deleteJson("/api/v1/profile/{$profile->id}");

        $response->assertStatus(204); // No Content

        // $response->assertStatus(200);
        // $response->assertJson(['message' => 'Deleted']);

        $this->assertDatabaseMissing('projects', [
            'id' => $profile->id,
        ]);
    }
}
