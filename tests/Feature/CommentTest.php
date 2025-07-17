<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_list_requires_authentication()
    {
        $response = $this->getJson('/api/v1/comment');
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_fetch_comment()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['read']);
        $response = $this->getJson('/api/v1/comment');
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_comment_on_project()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        Sanctum::actingAs($user,['create']);

        $data = [
            'user_id'=>$user->id,
            'commentable_id'=>$project->id,
            'commentable_type'=>Project::class,
            'body'=>'Comment on project',
        ];

        $response = $this->postJson('/api/v1/comment',$data);
        $response->assertStatus(201);

        $this->assertDatabaseHas('comments', [
            'user_id'=>$user->id,
            'commentable_id'=>$project->id,
            'commentable_type'=>Project::class,
            'body'=>'Comment on project',
        ]);
    }

    public function test_authenticated_user_can_create_comment_on_proposal()
    {
        $user = User::factory()->create();
        $proposal = Proposal::factory()->create();

        Sanctum::actingAs($user,['create']);

        $data = [
            'user_id'=>$user->id,
            'commentable_id'=>$proposal->id,
            'commentable_type'=>Proposal::class,
            'body'=>'Comment on proposal',
        ];

        $response = $this->postJson('/api/v1/comment',$data);
        $response->assertStatus(201);

        $this->assertDatabaseHas('comments', [
            'user_id'=>$user->id,
            'commentable_id'=>$proposal->id,
            'commentable_type'=>Proposal::class,
            'body'=>'Comment on proposal',
        ]);
    }

    public function test_authenticated_user_can_update_comment_on_project_via_put_method()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        Sanctum::actingAs($user, ['update']);

        $comment = Comment::factory()->create([
            'user_id'=>$user->id,
            'commentable_id'=>$project->id,
            'commentable_type'=>Project::class,
            'body'=>'Comment on project',
        ]);

        $data = [
            'user_id'=>$user->id,
            'commentable_id'=>$project->id,
            'commentable_type'=>Project::class,
            'body'=>'Modification comment here',
        ];

        $response = $this->putJson("/api/v1/comment/{$comment->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('comments', [
            'user_id'=>$user->id,
            'commentable_id'=>$project->id,
            'commentable_type'=>Project::class,
            'body'=>'Modification comment here',
        ]);
    }

    public function test_authenticated_user_can_update_comment_on_proposal_via_put_method()
    {
        $user = User::factory()->create();
        $proposal = Proposal::factory()->create();

        Sanctum::actingAs($user, ['update']);

        $comment = Comment::factory()->create([
            'user_id'=>$user->id,
            'commentable_id'=>$proposal->id,
            'commentable_type'=>Proposal::class,
            'body'=>'Comment on proposal',
        ]);

        $data = [
            'user_id'=>$user->id,
            'commentable_id'=>$proposal->id,
            'commentable_type'=>Proposal::class,
            'body'=>'Modification comment on proposal',
        ];

        $response = $this->putJson("/api/v1/comment/{$comment->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('comments', [
            'user_id'=>$user->id,
            'commentable_id'=>$proposal->id,
            'commentable_type'=>Proposal::class,
            'body'=>'Modification comment on proposal',
        ]);
    }

    public function test_authenticated_user_can_update_comment_on_project_via_patch_method()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        Sanctum::actingAs($user, ['update']);

        $comment = Comment::factory()->create([
            'user_id'=>$user->id,
            'commentable_id'=>$project->id,
            'commentable_type'=>Project::class,
            'body'=>'Comment on project',
        ]);

        $data = [
            'body'=>'Modification project comment here',
        ];

        $response = $this->patchJson("/api/v1/comment/{$comment->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('comments', [
            'user_id'=>$user->id,
            'commentable_id'=>$project->id,
            'commentable_type'=>Project::class,
            'body'=>'Modification project comment here',
        ]);
    }

    public function test_authenticated_user_can_update_comment_on_proposal_via_patch_method()
    {
        $user = User::factory()->create();
        $proposal = Proposal::factory()->create();

        Sanctum::actingAs($user, ['update']);

        $comment = Comment::factory()->create([
            'user_id'=>$user->id,
            'commentable_id'=>$proposal->id,
            'commentable_type'=>Proposal::class,
            'body'=>'Comment on proposal',
        ]);

        $data = [
            'body'=>'Modification comment on proposal',
        ];

        $response = $this->patchJson("/api/v1/comment/{$comment->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('comments', [
            'user_id'=>$user->id,
            'commentable_id'=>$proposal->id,
            'commentable_type'=>Proposal::class,
            'body'=>'Modification comment on proposal',
        ]);
    }

    public function test_authenticated_user_can_delete_comment_on_project()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        Sanctum::actingAs($user, ['delete']);

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'commentable_id'=>$project->id,
            'commentable_type'=>Project::class,
            'body'=>'Comment on project',
        ]);

        $response = $this->deleteJson("/api/v1/comment/{$comment->id}");

        $response->assertStatus(204); // No Content

        // $response->assertStatus(200);
        // $response->assertJson(['message' => 'Deleted']);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }

    public function test_authenticated_user_can_delete_comment_on_proposal()
    {
        $user = User::factory()->create();
        $proposal = Proposal::factory()->create();

        Sanctum::actingAs($user, ['delete']);

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'commentable_id'=>$proposal->id,
            'commentable_type'=>Proposal::class,
            'body'=>'Comment on project',
        ]);

        $response = $this->deleteJson("/api/v1/comment/{$comment->id}");

        $response->assertStatus(204); // No Content

        // $response->assertStatus(200);
        // $response->assertJson(['message' => 'Deleted']);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }
}
