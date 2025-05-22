<?php

namespace App\Services\Floor;

use App\Models\Floor;
use App\Models\Project;

class FloorService
{
    public function getFloors(Project $project, bool $loadElements = false): mixed
    {
        $floors = Floor::where('project_id', $project->id);
        if ($loadElements) {
            $floors->with('elements');
        }

        return $floors->get();
    }

    public function createFloor(array $data): Floor
    {
        $floor = Floor::create($data);

        return $floor;
    }
}
