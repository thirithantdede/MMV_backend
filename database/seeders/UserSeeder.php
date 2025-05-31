<?php

namespace Database\Seeders;

use App\Models\BuildingFootprint;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = ['userA', 'userB','userC','userD','thirithant'];

        foreach ($users as $user) {
            $newUser = \App\Models\User::factory()->create([
                'name' => $user,
                'email' => $user.'@gmail.com',
                'password' => bcrypt('password'),
            ]);

            $project = Project::create([
                'name' => $user.'-project',
                'description' => 'project description',
                'address' => fake()->address(),
                'website' => fake()->url(),
                'is_published' => false,
                'is_public' => false,
                'published_at' => now(),
                'user_id' => $newUser->id,
            ]);

            BuildingFootprint::create([
                'project_id' => $project->id,
            ]);

            $floors = [];
            for ($i = 1; $i <= 4; $i++) {
                $floors[] = [
                    'id' => Str::ulid(),
                    'name' => $user.'-floor-'.$i,
                    'level' => $i,
                    'grid_size' => 20,
                    'walking_paths' => null,
                    'project_id' => $project->id,
                ];
            }

            \App\Models\Floor::insert($floors);
        }
    }
}
