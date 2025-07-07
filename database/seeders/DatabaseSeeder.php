<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Country;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\Tag;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // 1️⃣ Create Countries
        $countries = Country::factory(5)->create();

        // 2️⃣ Create Skills & Tags
        $skills = Skill::factory(5)->create();
        $tags = Tag::factory(5)->create();

        // 3️⃣ Create Users with Profiles, Projects, Proposals
        User::factory(10)
            ->has(Profile::factory())
            ->has(Project::factory()->count(2))
            ->has(Proposal::factory()->count(1))
            ->create()
            ->each(function ($user) use ($skills, $tags) 
            {

                // Attach random Skills to each user
                $user->skills()->attach($skills->random(2));

                // Create Comments on Projects & Proposals (Polymorphic)
                $user->projects->each(function ($project) use ($user, $tags) {
                    // Add 2 comments on each project
                    Comment::factory(2)->create([
                        'user_id' => $user->id,
                        'commentable_id' => $project->id,
                        'commentable_type' => Project::class,
                    ]);

                    // Attach random Tags to Project (Polymorphic many-to-many)
                    $project->tags()->attach($tags->random(2));
                });

                $user->proposals->each(function ($proposal) use ($user, $tags) {
                    // Add 1 comment on each proposal
                    Comment::factory()->create([
                        'user_id' => $user->id,
                        'commentable_id' => $proposal->id,
                        'commentable_type' => Proposal::class,
                    ]);

                    // Attach random Tags to Proposal
                    $proposal->tags()->attach($tags->random(1));
                });
            });
    }
}
