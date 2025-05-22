<?php

namespace App\Services\Element;

use App\Models\Project;
use App\Services\Floor\FloorService;

class ElementService
{
    public function getElements(Project $project)
    {
        $elementsByFloors = (new FloorService)->getFloors($project, true);

        return $elementsByFloors;
    }
}
