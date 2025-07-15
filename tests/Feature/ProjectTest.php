<?php

namespace Tests\Feature;

use App\Models\Project;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_list_requires_authentication()
    {
        $response = $this->getJson('/api/v1/project');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_fetch_projects()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['read']); //token

        $response = $this->getJson('/api/v1/project');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_project()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['create']); //token

        $data = [
            'user_id' => $user->id,
            'title' => 'API Test Project',
            'description' => 'Project created via API test',
        ];

        $response = $this->postJson('/api/v1/project', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('projects', [
            'title' => 'API Test Project',
            'user_id' => $user->id, 
        ]);
    }

    public function test_authenticated_user_can_update_project_with_put()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['update']);

        $project = Project::factory()->create([
            'user_id' => $user->id,
            'title' => 'Old Title',
            'description' => 'Old description',
        ]);

        $data = [
            'user_id' => $user->id,
            'title' => 'Updated Title',
            'description' => 'Updated description',
        ];

        $response = $this->putJson("/api/v1/project/{$project->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'user_id' => $user->id,
        ]);
    }

    public function test_authenticated_user_can_update_project_with_patch()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['update']);

        $project = Project::factory()->create([
            'user_id' => $user->id,
            'title' => 'Original Title',
            'description' => 'Original description',
        ]);

        $data = [
            'title' => 'Patched Title',
            'user_id' => $user->id,
        ];

        $response = $this->patchJson("/api/v1/project/{$project->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => 'Patched Title',
            'description' => 'Original description', // not changed
            'user_id' => $user->id,
        ]);
    }

    public function test_authenticated_user_can_delete_project()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['delete']);

        $project = Project::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->deleteJson("/api/v1/project/{$project->id}");

        //$response->assertStatus(204); // No Content

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Deleted']);

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
        ]);
    }


}
