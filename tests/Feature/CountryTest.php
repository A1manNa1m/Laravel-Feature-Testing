<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CountryTest extends TestCase
{
    use RefreshDatabase;

    public function test_country_list_requires_authentication()
    {
        $response = $this->getJson('/api/v1/country');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_fetch_country()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['read']); //token

        $response = $this->getJson('/api/v1/country');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_country()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['create']); //token

        $data = [
            'name' => 'Malaysia',
        ];

        $response = $this->postJson('/api/v1/country', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('countries', [
            'name' => 'Malaysia',
        ]);
    }

    public function test_authenticated_user_can_update_country_with_put()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['update']);

        $country = Country::factory()->create([
            'name' => 'Japan',
        ]);

        $data = [
            'name' => 'Thailand',
        ];

        $response = $this->putJson("/api/v1/country/{$country->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('countries', [
            'name' => 'Thailand',
        ]);
    }

    public function test_authenticated_user_can_update_project_with_patch()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['update']);

        $country = Country::factory()->create([
            'name' => 'Turkeys',
        ]);

        $data = [
            'name' => 'Turkey',
        ];

        $response = $this->patchJson("/api/v1/country/{$country->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('countries', [
            'name' => 'Turkey',
        ]);
    }

    public function test_authenticated_user_can_delete_project()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['delete']);

        $country = Country::factory()->create();

        $response = $this->deleteJson("/api/v1/country/{$country->id}");

        $response->assertStatus(204); // No Content

        // $response->assertStatus(200);
        // $response->assertJson(['message' => 'Deleted']);

        $this->assertDatabaseMissing('projects', [
            'id' => $country->id,
        ]);
    }
}
