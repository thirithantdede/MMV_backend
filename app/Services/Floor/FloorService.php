<?php

namespace App\Services\Floor;

use App\Models\Element;
use App\Models\Floor;
use App\Models\Project;

class FloorService
{
    public function getFloors(Project $project, bool $loadElements = false): mixed
{
    $floorsQuery = Floor::where('project_id', $project->id);

    if ($loadElements) {
        $floorsQuery->with('elements.shopInformation');
    }

    $floors = $floorsQuery->get();

    // If not loading elements, just return floors
    if (!$loadElements) {
        return $floors;
    }

    // Get the extra elements that are not assigned to any floor
    $unassignedElements = Element::where([
        'project_id' => $project->id,
        'floor_id' => 0
    ])->get();

    // Append unassigned elements to the first floor's elements, without overwriting
    if ($floors->isNotEmpty()) {
        $firstFloor = $floors->first();

        // Merge unassigned elements without overwriting the existing ones
        $firstFloor->setRelation(
            'elements',
            $firstFloor->elements->concat($unassignedElements)
        );
    }

    return $floors;
}



    public function createFloor(array $data): Floor
    {
        $floor = Floor::create($data);

        return $floor;
    }
}
