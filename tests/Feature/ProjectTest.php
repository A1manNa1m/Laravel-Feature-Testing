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
            'objective' => 'Project', //optional field
        ];

        $response = $this->postJson('/api/v1/project', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('projects', [
            'user_id' => $user->id,
            'title' => 'API Test Project',
            'description' => 'Project created via API test',
            'objective' => 'Project',
        ]);
    }

    public function test_it_creates_a_project_when_all_fields_are_valid()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['create']); //token

        $response = $this->postJson('/api/v1/project', [
            'user_id' => $user->id,
            'title' => 'Test Project',
            'description' => 'Project created via API',
            'objective' => 'Project111', //optional
        ]);

        $response->assertStatus(201); // Created
        $this->assertDatabaseHas('projects', [
            'user_id' => $user->id,
            'title' => 'Test Project',
            'description' => 'Project created via API',
            'objective' => 'Project111',
        ]);
    }

    public function test_it_returns_validation_errors_when_required_fields_are_missing()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['create']); //token

        $requiredFields = ['user_id', 'title','description','description'];

        foreach ($requiredFields as $field) {
            $data = [
                'user_id' => $user->id,
                'title' => 'Sample Project',
                'description' => 'Test project description',
                'objective' => 'Project111-08-01', // optional
            ];

            unset($data[$field]); // Remove the required field we’re testing

            $response = $this->postJson('/api/v1/project', $data);

            $response->assertStatus(422);
            $response->assertJsonValidationErrors($field);
        }
    }

    public function test_it_allows_optional_fields_to_be_omitted()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['create']); //token

        // Only send required fields (omit `due_date`)
        $response = $this->postJson('/api/v1/project', [
            'user_id' => $user->id,
            'title' => 'Project Without objective',
            'description' => 'This one has no objective field',
        ]);

        $response->assertStatus(201); // Should still create successfully
        $this->assertDatabaseHas('projects', [
            'user_id' => $user->id,
            'title' => 'Project Without objective',
            'description' => 'This one has no objective field',
            'objective' => null, // Optional field defaults to null
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
            'objective' => 'Project111',
        ]);

        $data = [
            'user_id' => $user->id,
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'objective' => 'Project222',
        ];

        $response = $this->putJson("/api/v1/project/{$project->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('projects', [
            'user_id' => $user->id,
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'objective' => 'Project222',
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
            'objective' => null,
        ]);

        $data = [
            'title' => 'Patched Title',
            'objective' => 'project9999',
        ];

        $response = $this->patchJson("/api/v1/project/{$project->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('projects', [
            'user_id' => $user->id, // not changed
            'title' => 'Patched Title',//changed
            'description' => 'Original description', // not changed
            'objective' => 'project9999', //changed
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
