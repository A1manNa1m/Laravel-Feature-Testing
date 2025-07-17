<?php

namespace Tests\Feature;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SkillTest extends TestCase
{
    use RefreshDatabase;

    public function test_skill_list_requires_authentication()
    {
        $response = $this->getJson('/api/v1/skill');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_fetch_skill()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['read']); //token

        $response = $this->getJson('/api/v1/skill');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_skill()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['create']); //token

        $data = [
            'name' => 'Random',
        ];

        $response = $this->postJson('/api/v1/skill', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('skills', [
            'name' => 'Random',
        ]);
    }

    public function test_authenticated_user_can_update_skill_with_put()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['update']);

        $skill = Skill::factory()->create([
            'name' => 'Coding',
        ]);

        $data = [
            'name' => 'Drawing',
        ];

        $response = $this->putJson("/api/v1/skill/{$skill->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('skills', [
            'name' => 'Drawing',
        ]);
    }

    public function test_authenticated_user_can_update_skill_with_patch()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['update']);

        $skill = Skill::factory()->create([
            'name' => 'Skill',
        ]);

        $data = [
            'name' => 'Cooking',
        ];

        $response = $this->patchJson("/api/v1/skill/{$skill->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('skills', [
            'name' => 'Cooking',
        ]);
    }

    public function test_authenticated_user_can_delete_project()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['delete']);

        $skill = Skill::factory()->create();

        $response = $this->deleteJson("/api/v1/skill/{$skill->id}");

        $response->assertStatus(204); // No Content

        // $response->assertStatus(200);
        // $response->assertJson(['message' => 'Deleted']);

        $this->assertDatabaseMissing('skills', [
            'id' => $skill->id,
        ]);
    }
    
}
