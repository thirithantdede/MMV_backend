<?php

namespace App\Services\Auth;

use App\Models\BuildingFootprint;
use App\Models\Project;
use App\Models\User;
use App\Traits\HandleAuth;
use Illuminate\Support\Str;


class AuthService
{
    use HandleAuth;

    public function newProjectSetup(array $user): void
    {
        $project = Project::create([
            'name' => $user['name'].'-project',
            'description' => 'project description',
            'address' =>    '',
            'website' => '',
            'is_published' => false,
            'is_public' => false,
            'published_at' => null,
            'user_id' => $user['id'],
        ]);

        BuildingFootprint::create([
            'project_id' => $project->id,
        ]);

        $floors = [];
        for ($i = 1; $i <= 4; $i++) {
            $floors[] = [
                'id' => Str::ulid(),
                'name' => $user['name'].'-floor-'.$i,
                'level' => $i,
                'grid_size' => 20,
                'walking_paths' => null,
                'project_id' => $project->id,
            ];
        }

        \App\Models\Floor::insert($floors);
    }
}
