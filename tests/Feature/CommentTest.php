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

    public function test_authenticated_user_can_create_comment()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['create']); //token
        
        $models = [
            [Project::class, 'project'],
            [Proposal::class, 'proposal'],
        ];

        foreach ($models as [$modelClass, $type]) {
            $model = $modelClass::factory()->create();
            
            $response = $this->postJson('/api/v1/comment', [
                'user_id'=>$user->id,
                'commentable_id'=>$model->id,
                'commentable_type'=>$modelClass,
                'body'=>"Comment on {$type}",
            ]);

            $response->assertStatus(201); // Created
            $this->assertDatabaseHas('comments', [
                'user_id'=>$user->id,
                'commentable_id'=>$model->id,
                'commentable_type'=>$modelClass,
                'body'=>"Comment on {$type}",
            ]);
        }
    }

    public function test_it_creates_a_comment_when_all_fields_are_valid()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['create']); //token
        
        $models = [
            [Project::class, 'project'],
            [Proposal::class, 'proposal'],
        ];

        foreach ($models as [$modelClass, $type]) {
            $model = $modelClass::factory()->create();
            
            $response = $this->postJson('/api/v1/comment', [
                'user_id'=>$user->id,
                'commentable_id'=>$model->id,
                'commentable_type'=>$modelClass,
                'body'=>"Comment on {$type}",
            ]);

            $response->assertStatus(201); // Created
            $this->assertDatabaseHas('comments', [
                'user_id'=>$user->id,
                'commentable_id'=>$model->id,
                'commentable_type'=>$modelClass,
                'body'=>"Comment on {$type}",
            ]);
        }
    }

    public function test_it_returns_validation_errors_when_required_fields_are_missing()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['create']); //token

        $models = [
            [Project::class, 'project'],
            [Proposal::class, 'proposal'],
        ];

        $requiredFields = ['user_id', 'commentable_id','commentable_type','body'];

        foreach ($models as [$modelClass, $type]) {

            $model = $modelClass::factory()->create();

            foreach ($requiredFields as $field) {
                $data = [
                    'user_id'=>$user->id,
                    'commentable_id'=>$model->id,
                    'commentable_type'=>$modelClass,
                    'body'=>"Comment on {$type}",
                ];

                unset($data[$field]); // Remove the required field we’re testing

                $response = $this->postJson('/api/v1/comment', $data);

                $response->assertStatus(422);
                $response->assertJsonValidationErrors($field);
            }
        }
    }

    public function test_authenticated_user_can_update_comment_via_put_method()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['update']);

        $models = [
            [Project::class, 'project'],
            [Proposal::class, 'proposal'],
        ];

        foreach ($models as [$modelClass, $type]) {
            $model = $modelClass::factory()->create();
            
            $comment = Comment::factory()->create([
                'user_id'=>$user->id,
                'commentable_id'=>$model->id,
                'commentable_type'=>$modelClass,
                'body'=>"Comment on {$type}",
            ]);

            $data = [
                'user_id'=>$user->id,
                'commentable_id'=>$model->id,
                'commentable_type'=>$modelClass,
                'body'=>"Modification comment on {$type}",
            ];

            $response = $this->putJson("/api/v1/comment/{$comment->id}", $data);
            $response->assertStatus(200);

            $this->assertDatabaseHas('comments', [
                'user_id'=>$user->id,
                'commentable_id'=>$model->id,
                'commentable_type'=>$modelClass,
                'body'=>"Modification comment on {$type}",
            ]);
        }
    }

    public function test_authenticated_user_can_update_comment_via_patch_method()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['update']);

        $models = [
            [Project::class, 'project'],
            [Proposal::class, 'proposal'],
        ];

        foreach ($models as [$modelClass, $type]) {
            $model = $modelClass::factory()->create();
            
            $comment = Comment::factory()->create([
                'user_id'=>$user->id,
                'commentable_id'=>$model->id,
                'commentable_type'=>$modelClass,
                'body'=>"Comment random",
            ]);

            $data = [
                'body'=>"Modification comment on {$type}",
            ];

            $response = $this->patchJson("/api/v1/comment/{$comment->id}", $data);
            $response->assertStatus(200);

            $this->assertDatabaseHas('comments', [
                'user_id'=>$user->id,
                'commentable_id'=>$model->id,
                'commentable_type'=>$modelClass,
                'body'=>"Modification comment on {$type}",
            ]);
        }
    }

    public function test_authenticated_user_can_delete_comment_on_project()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['delete']);
        
        $models = [
            [Project::class, 'project'],
            [Proposal::class, 'proposal'],
        ];

        foreach ($models as [$modelClass, $type]) {
            $model = $modelClass::factory()->create();
            
            $comment = Comment::factory()->create([
                'user_id'=>$user->id,
                'commentable_id'=>$model->id,
                'commentable_type'=>$modelClass,
                'body'=>"Comment on {$type}",
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
}
