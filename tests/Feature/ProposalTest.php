<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProposalTest extends TestCase
{
    use RefreshDatabase;

    public function test_proposal_list_requires_authentication()
    {
        $response = $this->getJson('/api/v1/proposal');
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_fetch_proposal()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['read']);
        $response = $this->getJson('/api/v1/proposal');
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_proposal()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        Sanctum::actingAs($user,['create']);

        $data = [
            'user_id'=>$user->id,
            'project_id'=>$project->id,
            'cover_letter'=>'Cover letter made via manual testing'
        ];

        $response = $this->postJson('/api/v1/proposal',$data);
        $response->assertStatus(201);
        $this->assertDatabaseHas('proposals', [
            'user_id'=>$user->id,
            'project_id'=>$project->id,
            'cover_letter'=>'Cover letter made via manual testing'
        ]);
    }

    public function test_it_creates_a_proposal_when_all_fields_are_valid()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        Sanctum::actingAs($user, ['create']); //token

        $response = $this->postJson('/api/v1/proposal', [
            'user_id'=>$user->id,
            'project_id'=>$project->id,
            'cover_letter'=>'All fields are valid'
        ]);

        $response->assertStatus(201); // Created
        $this->assertDatabaseHas('proposals', [
            'user_id'=>$user->id,
            'project_id'=>$project->id,
            'cover_letter'=>'All fields are valid'
        ]);
    }

    public function test_it_returns_validation_errors_when_required_fields_are_missing()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        Sanctum::actingAs($user, ['create']); //token

        $requiredFields = ['user_id', 'project_id','cover_letter'];

        foreach ($requiredFields as $field) {
            $data = [
                'user_id'=>$user->id,
                'project_id'=>$project->id,
                'cover_letter'=>'All fields are valid'
            ];

            unset($data[$field]); // Remove the required field we’re testing

            $response = $this->postJson('/api/v1/proposal', $data);

            $response->assertStatus(422);
            $response->assertJsonValidationErrors($field);
        }
    }

    public function test_authenticated_user_can_update_proposal_with_put()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        Sanctum::actingAs($user, ['update']);

        $proposal = Proposal::factory()->create([
            'user_id'=>$user->id,
            'project_id'=>$project->id,
            'cover_letter'=>'Cover letter first'
        ]);

        $data = [
            'user_id'=>$user->id,
            'project_id'=>$project->id,
            'cover_letter'=>'updated cover letter'
        ];

        $response = $this->putJson("/api/v1/proposal/{$proposal->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('proposals', [
            'id' => $proposal->id,
            'user_id'=>$user->id,
            'project_id'=>$project->id,
            'cover_letter'=>'updated cover letter'
        ]);
    }

    public function test_authenticated_user_can_update_proposal_with_patch()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        Sanctum::actingAs($user, ['update']);

        $proposal = Proposal::factory()->create([
            'user_id'=>$user->id,
            'project_id'=>$project->id,
            'cover_letter'=>'Cover letter first'
        ]);

        $data = [
            'cover_letter'=>'updated cover letter second time'
        ];

        $response = $this->patchJson("/api/v1/proposal/{$proposal->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('proposals', [
            'id' => $proposal->id,
            'user_id'=>$user->id,
            'project_id'=>$project->id,
            'cover_letter'=>'updated cover letter second time'
        ]);
    }

    public function test_authenticated_user_can_delete_project()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        Sanctum::actingAs($user, ['delete']);

        $proposal = Proposal::factory()->create([
            'user_id' => $user->id,
            'project_id' => $project->id,
        ]);

        $response = $this->deleteJson("/api/v1/proposal/{$proposal->id}");

        $response->assertStatus(204); // No Content

        // $response->assertStatus(200);
        // $response->assertJson(['message' => 'Deleted']);

        $this->assertDatabaseMissing('proposals', [
            'id' => $proposal->id,
        ]);
    }

}
