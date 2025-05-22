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
        $users = ['userA', 'userB'];

        foreach ($users as $user) {
            $newUser = \App\Models\User::factory()->create([
                'name' => $user,
                'email' => $user.'@gmail.com',
                'password' => bcrypt('password'),
            ]);

            $project = Project::create([
                'name' => $user.'-project',
                'description' => 'description',
                'address' => 'address',
                'website' => 'website',
                'grid_size' => 20,
                'business_hours' => '1',
                'building_footprint' => json_encode([]),
                'is_published' => true,
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
